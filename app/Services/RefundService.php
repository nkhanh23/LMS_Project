<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RefundRequest;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\RefundRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService
{
    protected $refundRepository;
    protected $orderRepository;

    public function __construct(RefundRepository $refundRepository, OrderRepository $orderRepository)
    {
        $this->refundRepository = $refundRepository;
        $this->orderRepository = $orderRepository;
    }

    public function getRefundRequests(array $filters = [], int $perPage = 15)
    {
        return $this->refundRepository->getRefundRequestsPaginated($filters, $perPage);
    }

    public function createUserRefundRequest(int $orderId, User $user, array $data): RefundRequest
    {
        return DB::transaction(function () use ($orderId, $user, $data) {
            $order = $this->orderRepository->lockOrderById($orderId);

            if ((int) $order->user_id !== (int) $user->id) {
                throw ValidationException::withMessages([
                    'order' => 'Bạn không có quyền thao tác order này.',
                ]);
            }

            if ($order->status !== 'completed') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ order completed mới được gửi yêu cầu refund.',
                ]);
            }

            if (in_array($order->refund_status, ['requested', 'approved', 'processed'])) {
                throw ValidationException::withMessages([
                    'order' => 'Order đang có workflow refund hoặc đã refund.',
                ]);
            }

            $openRequest = $this->refundRepository->findOpenRefundRequestByOrderId($order->id);
            if ($openRequest) {
                throw ValidationException::withMessages([
                    'order' => 'Order này đã có yêu cầu refund đang mở.',
                ]);
            }

            $requestedAmount = isset($data['requested_amount']) && $data['requested_amount'] !== null
                ? (float) $data['requested_amount']
                : (float) ($order->gross_amount ?? $order->price ?? 0);

            $refundRequest = $this->refundRepository->createRefundRequest([
                'order_id'          => $order->id,
                'payment_id'        => $order->payment_id,
                'user_id'           => $user->id,
                'request_source'    => 'user',
                'type'              => $data['type'] ?? 'refund',
                'status'            => 'pending',
                'requested_amount'  => $requestedAmount,
                'reason'            => $data['reason'],
                'requested_at'      => now(),
            ]);

            $this->refundRepository->updateOrder($order, [
                'refund_status'       => 'requested',
                'refund_reason'       => $data['reason'],
                'refund_requested_at' => now(),
            ]);

            $this->refundRepository->createStatusHistory([
                'order_id'            => $order->id,
                'payment_id'          => $order->payment_id,
                'from_status'         => $order->status,
                'to_status'           => $order->status,
                'from_refund_status'  => 'none',
                'to_refund_status'    => 'requested',
                'action'              => 'user_refund_request',
                'actor_id'            => $user->id,
                'actor_role'          => $user->role,
                'note'                => $data['reason'],
                'meta_json'           => [
                    'type' => $data['type'] ?? 'refund',
                    'requested_amount' => $requestedAmount,
                ],
            ]);

            return $refundRequest;
        });
    }

    public function approveRefundRequest(int $refundRequestId, User $admin, array $data): RefundRequest
    {
        return DB::transaction(function () use ($refundRequestId, $admin, $data) {
            $refundRequest = $this->refundRepository->findRefundRequestById($refundRequestId);
            $order = $this->orderRepository->lockOrderById($refundRequest->order_id);

            if ($refundRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'refund_request' => 'Yêu cầu này không còn ở trạng thái pending.',
                ]);
            }

            $approvedAmount = isset($data['approved_amount']) && $data['approved_amount'] !== null
                ? (float) $data['approved_amount']
                : (float) ($refundRequest->requested_amount ?? $order->gross_amount ?? $order->price ?? 0);

            $newOrderStatus = $refundRequest->type === 'cancel' ? 'cancelled' : 'refunded';

            $this->refundRepository->updateRefundRequest($refundRequest, [
                'status'          => 'processed',
                'approved_amount' => $approvedAmount,
                'admin_note'      => $data['admin_note'] ?? null,
                'reviewed_by'     => $admin->id,
                'reviewed_at'     => now(),
                'processed_by'    => $admin->id,
                'processed_at'    => now(),
            ]);

            $this->refundRepository->updateOrder($order, [
                'status'            => $newOrderStatus,
                'refund_status'     => 'processed',
                'refund_amount'     => $approvedAmount,
                'refund_reason'     => $refundRequest->type === 'refund' ? $refundRequest->reason : $order->refund_reason,
                'cancel_reason'     => $refundRequest->type === 'cancel' ? $refundRequest->reason : $order->cancel_reason,
                'refunded_at'       => $refundRequest->type === 'refund' ? now() : $order->refunded_at,
                'refunded_by'       => $refundRequest->type === 'refund' ? $admin->id : $order->refunded_by,
                'cancelled_at'      => $refundRequest->type === 'cancel' ? now() : $order->cancelled_at,
                'cancelled_by'      => $refundRequest->type === 'cancel' ? $admin->id : $order->cancelled_by,
                'access_revoked_at' => now(),
            ]);

            if ($order->payment) {
                $paymentTotal = $this->normalizeMoney($order->payment->total_amount);
                $newRefundedAmount = (float) $order->payment->refunded_amount + $approvedAmount;

                $paymentStatus = $newRefundedAmount >= $paymentTotal
                    ? 'refunded'
                    : 'partially_refunded';

                $this->refundRepository->updatePayment($order->payment, [
                    'status'           => $paymentStatus,
                    'provider_status'  => $paymentStatus,
                    'refunded_amount'  => $newRefundedAmount,
                    'refunded_at'      => now(),
                    'refund_reference' => 'manual-admin-' . now()->format('YmdHis'),
                ]);
            }

            $this->refundRepository->createStatusHistory([
                'order_id'           => $order->id,
                'payment_id'         => $order->payment_id,
                'from_status'        => $order->status,
                'to_status'          => $newOrderStatus,
                'from_refund_status' => $order->refund_status,
                'to_refund_status'   => 'processed',
                'action'             => 'admin_refund_approve',
                'actor_id'           => $admin->id,
                'actor_role'         => $admin->role,
                'note'               => $data['admin_note'] ?? null,
                'meta_json'          => [
                    'type' => $refundRequest->type,
                    'approved_amount' => $approvedAmount,
                ],
            ]);

            return $refundRequest->refresh();
        });
    }

    public function rejectRefundRequest(int $refundRequestId, User $admin, array $data): RefundRequest
    {
        return DB::transaction(function () use ($refundRequestId, $admin, $data) {
            $refundRequest = $this->refundRepository->findRefundRequestById($refundRequestId);
            $order = $this->orderRepository->lockOrderById($refundRequest->order_id);

            if ($refundRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'refund_request' => 'Yêu cầu này không còn ở trạng thái pending.',
                ]);
            }

            $this->refundRepository->updateRefundRequest($refundRequest, [
                'status'      => 'rejected',
                'admin_note'  => $data['admin_note'] ?? null,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            $this->refundRepository->updateOrder($order, [
                'refund_status' => 'rejected',
            ]);

            $this->refundRepository->createStatusHistory([
                'order_id'           => $order->id,
                'payment_id'         => $order->payment_id,
                'from_status'        => $order->status,
                'to_status'          => $order->status,
                'from_refund_status' => $order->refund_status,
                'to_refund_status'   => 'rejected',
                'action'             => 'admin_refund_reject',
                'actor_id'           => $admin->id,
                'actor_role'         => $admin->role,
                'note'               => $data['admin_note'] ?? null,
                'meta_json'          => [
                    'type' => $refundRequest->type,
                ],
            ]);

            return $refundRequest->refresh();
        });
    }

    public function manualRefund(int $orderId, User $admin, array $data): RefundRequest
    {
        return DB::transaction(function () use ($orderId, $admin, $data) {
            $order = $this->orderRepository->lockOrderById($orderId);

            if ($order->status !== 'completed') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ order completed mới manual refund được.',
                ]);
            }

            if (in_array($order->refund_status, ['requested', 'approved', 'processed'])) {
                throw ValidationException::withMessages([
                    'order' => 'Order đang có workflow refund hoặc đã refund.',
                ]);
            }

            $approvedAmount = (float) $data['approved_amount'];

            $refundRequest = $this->refundRepository->createRefundRequest([
                'order_id'          => $order->id,
                'payment_id'        => $order->payment_id,
                'user_id'           => $order->user_id,
                'request_source'    => 'admin',
                'type'              => 'refund',
                'status'            => 'processed',
                'requested_amount'  => $approvedAmount,
                'approved_amount'   => $approvedAmount,
                'reason'            => $data['reason'],
                'admin_note'        => $data['admin_note'] ?? null,
                'requested_at'      => now(),
                'reviewed_by'       => $admin->id,
                'reviewed_at'       => now(),
                'processed_by'      => $admin->id,
                'processed_at'      => now(),
            ]);

            $this->refundRepository->updateOrder($order, [
                'status'            => 'refunded',
                'refund_status'     => 'processed',
                'refund_amount'     => $approvedAmount,
                'refund_reason'     => $data['reason'],
                'refunded_at'       => now(),
                'refunded_by'       => $admin->id,
                'access_revoked_at' => now(),
            ]);

            if ($order->payment) {
                $paymentTotal = $this->normalizeMoney($order->payment->total_amount);
                $newRefundedAmount = (float) $order->payment->refunded_amount + $approvedAmount;

                $paymentStatus = $newRefundedAmount >= $paymentTotal
                    ? 'refunded'
                    : 'partially_refunded';

                $this->refundRepository->updatePayment($order->payment, [
                    'status'           => $paymentStatus,
                    'provider_status'  => $paymentStatus,
                    'refunded_amount'  => $newRefundedAmount,
                    'refunded_at'      => now(),
                    'refund_reference' => 'manual-admin-' . now()->format('YmdHis'),
                ]);
            }

            $this->refundRepository->createStatusHistory([
                'order_id'           => $order->id,
                'payment_id'         => $order->payment_id,
                'from_status'        => $order->status,
                'to_status'          => 'refunded',
                'from_refund_status' => $order->refund_status,
                'to_refund_status'   => 'processed',
                'action'             => 'admin_manual_refund',
                'actor_id'           => $admin->id,
                'actor_role'         => $admin->role,
                'note'               => $data['admin_note'] ?? null,
                'meta_json'          => [
                    'approved_amount' => $approvedAmount,
                ],
            ]);

            return $refundRequest;
        });
    }

    public function manualCancel(int $orderId, User $admin, array $data): RefundRequest
    {
        return DB::transaction(function () use ($orderId, $admin, $data) {
            $order = $this->orderRepository->lockOrderById($orderId);

            if ($order->status === 'completed') {
                throw ValidationException::withMessages([
                    'order' => 'Order đã completed thì nên dùng manual refund, không nên manual cancel.',
                ]);
            }

            if ($order->status === 'cancelled') {
                throw ValidationException::withMessages([
                    'order' => 'Order này đã cancelled.',
                ]);
            }

            $refundRequest = $this->refundRepository->createRefundRequest([
                'order_id'       => $order->id,
                'payment_id'     => $order->payment_id,
                'user_id'        => $order->user_id,
                'request_source' => 'admin',
                'type'           => 'cancel',
                'status'         => 'processed',
                'reason'         => $data['reason'],
                'admin_note'     => $data['admin_note'] ?? null,
                'requested_at'   => now(),
                'reviewed_by'    => $admin->id,
                'reviewed_at'    => now(),
                'processed_by'   => $admin->id,
                'processed_at'   => now(),
            ]);

            $this->refundRepository->updateOrder($order, [
                'status'            => 'cancelled',
                'refund_status'     => 'processed',
                'cancel_reason'     => $data['reason'],
                'cancelled_at'      => now(),
                'cancelled_by'      => $admin->id,
                'access_revoked_at' => now(),
            ]);

            $this->refundRepository->createStatusHistory([
                'order_id'           => $order->id,
                'payment_id'         => $order->payment_id,
                'from_status'        => $order->status,
                'to_status'          => 'cancelled',
                'from_refund_status' => $order->refund_status,
                'to_refund_status'   => 'processed',
                'action'             => 'admin_manual_cancel',
                'actor_id'           => $admin->id,
                'actor_role'         => $admin->role,
                'note'               => $data['admin_note'] ?? null,
                'meta_json'          => [],
            ]);

            return $refundRequest;
        });
    }

    protected function normalizeMoney($value): float
    {
        if ($value === null) {
            return 0;
        }

        $normalized = preg_replace('/[^0-9.\-]/', '', (string) $value);

        return (float) ($normalized ?: 0);
    }
}
