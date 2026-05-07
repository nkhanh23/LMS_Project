<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo yêu cầu rút tiền</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #007bff;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header.approved {
            background: #28a745;
        }
        .header.rejected {
            background: #dc3545;
        }
        .content {
            padding: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .status-approved {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-rejected {
            background: #ffebee;
            color: #c62828;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .details-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .details-label {
            font-weight: bold;
            color: #666;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $payout->status }}">
            <h2 style="margin:0;">Thông báo rút tiền</h2>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $payout->instructor->name }}</strong>,</p>

            @if($payout->status === 'approved')
                <div class="status-badge status-approved">Đã phê duyệt</div>
                <p>Chúng tôi vui mừng thông báo rằng yêu cầu rút tiền của bạn đã được xử lý thành công.</p>
            @else
                <div class="status-badge status-rejected">Bị từ chối</div>
                <p>Chúng tôi rất tiếc phải thông báo rằng yêu cầu rút tiền của bạn đã bị từ chối.</p>
            @endif

            <div class="details">
                <div class="details-item">
                    <span class="details-label">Mã giao dịch:</span>
                    <span>#{{ $payout->id }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Số tiền:</span>
                    <span style="font-weight:bold; color: #333;">{{ number_format($payout->amount, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Ngân hàng:</span>
                    <span>{{ $payout->bank_name }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Số tài khoản:</span>
                    <span>{{ $payout->account_number }}</span>
                </div>
                @if($payout->transaction_reference)
                <div class="details-item">
                    <span class="details-label">Mã tham chiếu:</span>
                    <span>{{ $payout->transaction_reference }}</span>
                </div>
                @endif
                <div class="details-item">
                    <span class="details-label">Thời gian:</span>
                    <span>{{ $payout->processed_at ? $payout->processed_at->format('H:i d/m/Y') : now()->format('H:i d/m/Y') }}</span>
                </div>
            </div>

            @if($payout->admin_note)
                <div style="margin-top: 20px; padding: 15px; border-left: 4px solid #ddd; background: #fefefe;">
                    <p style="margin:0; font-style: italic;"><strong>Ghi chú từ quản trị viên:</strong></p>
                    <p style="margin:5px 0 0 0;">{{ $payout->admin_note }}</p>
                </div>
            @endif

            <p style="margin-top: 30px;">Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với bộ phận hỗ trợ của chúng tôi.</p>
            <p>Trân trọng,<br>Đội ngũ StackLearn</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} StackLearn LMS. All rights reserved.
        </div>
    </div>
</body>
</html>
