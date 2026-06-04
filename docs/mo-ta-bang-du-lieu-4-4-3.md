4.4.3. M? t? c?c b?ng d? li?u ch?nh

D??i ??y l? m? t? c?c b?ng d? li?u ch?nh trong c? s? d? li?u c?a h? th?ng StackLearn. C?c b?ng ???c tr?nh b?y theo c?u tr?c g?m t?n c?t, ki?u d? li?u, kh? n?ng nh?n gi? tr? null, li?n k?t kh?a ngo?i v? ghi ch? ch?c n?ng c?a t?ng tr??ng.

- B?ng users

B?ng 4.1 D? li?u users

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| first_name | varchar(255) | c? |  |  |
| last_name | varchar(255) | c? |  |  |
| name | varchar(255) | kh?ng |  | T?n hi?n th? |
| email | varchar(255) | kh?ng |  | unique |
| email_verified_at | timestamp | c? |  |  |
| password | varchar(255) | kh?ng |  | M?t kh?u ?? m? h?a |
| photo | varchar(255) | c? |  |  |
| phone | varchar(255) | c? |  |  |
| address | varchar(255) | c? |  |  |
| role | varchar(255) | kh?ng |  | default = 'user' |
| status | varchar(255) | kh?ng |  | default = '1' |
| instructor_approval_status | varchar(255) | kh?ng |  | default = 'pending' |
| instructor_review_note | text | c? |  |  |
| instructor_reviewed_by | bigint | c? | users(id) |  |
| instructor_reviewed_at | timestamp | c? |  |  |
| bio | text | c? |  |  |
| day | int | c? |  |  |
| month | int | c? |  |  |
| year | int | c? |  |  |
| city | varchar(255) | c? |  |  |
| country | varchar(255) | c? |  |  |
| experience | text | c? |  |  |
| gender | varchar(255) | kh?ng |  | default = 'male' |
| remember_token | varchar(100) | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o first_name: tr??ng d? li?u c?a b?ng users.
o last_name: tr??ng d? li?u c?a b?ng users.
o name: T?n hi?n th?.
o email: unique.
o email_verified_at: tr??ng d? li?u c?a b?ng users.
o password: M?t kh?u ?? m? h?a.
o photo: tr??ng d? li?u c?a b?ng users.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng categories

B?ng 4.2 D? li?u categories

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| name | varchar(255) | kh?ng |  | T?n hi?n th? |
| slug | varchar(255) | kh?ng |  | ???ng d?n th?n thi?n |
| image | varchar(255) | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o name: T?n hi?n th?.
o slug: ???ng d?n th?n thi?n.
o image: tr??ng d? li?u c?a b?ng categories.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng sub_categories

B?ng 4.3 D? li?u sub_categories

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| category_id | bigint | kh?ng | categories(id) |  |
| name | varchar(255) | kh?ng |  | T?n hi?n th? |
| slug | varchar(255) | kh?ng |  | ???ng d?n th?n thi?n |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o category_id: li?n k?t t?i categories(id).
o name: T?n hi?n th?.
o slug: ???ng d?n th?n thi?n.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng sliders

B?ng 4.4 D? li?u sliders

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| title | varchar(255) | kh?ng |  | Ti?u ?? |
| short_description | text | kh?ng |  |  |
| video_url | varchar(255) | kh?ng |  |  |
| image | varchar(255) | kh?ng |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o title: Ti?u ??.
o short_description: tr??ng d? li?u c?a b?ng sliders.
o video_url: tr??ng d? li?u c?a b?ng sliders.
o image: tr??ng d? li?u c?a b?ng sliders.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng info_boxes

B?ng 4.5 D? li?u info_boxes

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| icon | varchar(255) | kh?ng |  |  |
| title | varchar(255) | kh?ng |  | Ti?u ?? |
| description | text | kh?ng |  | M? t? |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o icon: tr??ng d? li?u c?a b?ng info_boxes.
o title: Ti?u ??.
o description: M? t?.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng courses

B?ng 4.6 D? li?u courses

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| category_id | bigint | kh?ng | categories(id) |  |
| subcategory_id | bigint | kh?ng | sub_categories(id) |  |
| instructor_id | bigint | kh?ng | users(id) | M? gi?ng vi?n |
| course_image | varchar(255) | c? |  |  |
| course_title | text | c? |  |  |
| course_name | text | c? |  |  |
| course_name_slug | varchar(255) | c? |  |  |
| description | text | c? |  | M? t? |
| video_url | varchar(255) | c? |  |  |
| label | varchar(255) | c? |  |  |
| duration | varchar(255) | c? |  |  |
| resources | varchar(255) | c? |  |  |
| certificate | varchar(255) | c? |  |  |
| selling_price | int | c? |  |  |
| discount_price | int | c? |  |  |
| prerequisites | text | c? |  |  |
| bestseller | varchar(255) | c? |  |  |
| featured | varchar(255) | c? |  |  |
| highestrated | varchar(255) | c? |  |  |
| course_goals | json | c? |  |  |
| status | tinyint | kh?ng |  | default = 0 |
| approval_status | varchar(255) | kh?ng |  | default = 'draft' |
| content_unlock_mode | varchar(255) | kh?ng |  | default = 'free' |
| approval_note | text | c? |  |  |
| reviewed_by | bigint | c? | users(id) |  |
| reviewed_at | timestamp | c? |  |  |
| submitted_for_review_at | timestamp | c? |  |  |
| approved_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o category_id: li?n k?t t?i categories(id).
o subcategory_id: li?n k?t t?i sub_categories(id).
o instructor_id: M? gi?ng vi?n.
o course_image: tr??ng d? li?u c?a b?ng courses.
o course_title: tr??ng d? li?u c?a b?ng courses.
o course_name: tr??ng d? li?u c?a b?ng courses.
o course_name_slug: tr??ng d? li?u c?a b?ng courses.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng course_goals

B?ng 4.7 D? li?u course_goals

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| goal_name | text | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o goal_name: tr??ng d? li?u c?a b?ng course_goals.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng course_sections

B?ng 4.8 D? li?u course_sections

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| section_title | varchar(255) | kh?ng |  |  |
| sort_order | int | kh?ng |  | default = 1 |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o section_title: tr??ng d? li?u c?a b?ng course_sections.
o sort_order: default = 1.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng course_lectures

B?ng 4.9 D? li?u course_lectures

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | c? | courses(id) | M? kh?a h?c |
| section_id | bigint | c? | course_sections(id) | M? ch??ng h?c |
| lecture_title | varchar(255) | c? |  |  |
| sort_order | int | kh?ng |  | default = 1 |
| is_preview | boolean | kh?ng |  | default = false |
| type | varchar(20) | kh?ng |  | default = 'video' |
| url | varchar(255) | c? |  |  |
| file_name | varchar(255) | c? |  |  |
| mime_type | varchar(255) | c? |  |  |
| file_size | bigint | c? |  |  |
| storage_disk | varchar(255) | c? |  |  |
| content | text | c? |  | N?i dung |
| video_duration | decimal(8,2) | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o section_id: M? ch??ng h?c.
o lecture_title: tr??ng d? li?u c?a b?ng course_lectures.
o sort_order: default = 1.
o is_preview: default = false.
o type: default = 'video'.
o url: tr??ng d? li?u c?a b?ng course_lectures.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng wishlists

B?ng 4.10 D? li?u wishlists

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng carts

B?ng 4.11 D? li?u carts

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| user_id | bigint | c? | users(id) | M? ng??i d?ng |
| guest_token | uuid | c? |  |  |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| quantity | int | kh?ng |  | default = 1 |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o user_id: M? ng??i d?ng.
o guest_token: tr??ng d? li?u c?a b?ng carts.
o course_id: M? kh?a h?c.
o quantity: default = 1.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng coupons

B?ng 4.12 D? li?u coupons

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| instructor_id | int | kh?ng | users(id) | M? gi?ng vi?n |
| coupon_code | varchar(255) | kh?ng |  |  |
| coupon_discount | varchar(255) | kh?ng |  |  |
| discount_validity | varchar(255) | kh?ng |  |  |
| status | int | kh?ng |  | default = 1 |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o instructor_id: M? gi?ng vi?n.
o coupon_code: tr??ng d? li?u c?a b?ng coupons.
o coupon_discount: tr??ng d? li?u c?a b?ng coupons.
o discount_validity: tr??ng d? li?u c?a b?ng coupons.
o status: default = 1.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng striipes

B?ng 4.13 D? li?u striipes

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| publish_key | varchar(255) | c? |  |  |
| secret_key | varchar(255) | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o publish_key: tr??ng d? li?u c?a b?ng striipes.
o secret_key: tr??ng d? li?u c?a b?ng striipes.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng orders

B?ng 4.14 D? li?u orders

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| payment_id | int | kh?ng | payments(id) | M? thanh to?n |
| user_id | int | c? | users(id) | M? ng??i d?ng |
| course_id | int | c? | courses(id) | M? kh?a h?c |
| instructor_id | int | c? | users(id) | M? gi?ng vi?n |
| course_title | varchar(255) | c? |  |  |
| price | int | c? |  |  |
| status | varchar(255) | kh?ng |  | default = 'completed' |
| refund_status | varchar(255) | kh?ng |  | default = 'none' |
| refund_amount | decimal(12,2) | kh?ng |  | default = 0 |
| refund_reason | text | c? |  |  |
| cancel_reason | text | c? |  |  |
| refund_requested_at | timestamp | c? |  |  |
| refunded_at | timestamp | c? |  |  |
| refunded_by | bigint | c? |  |  |
| cancelled_at | timestamp | c? |  |  |
| cancelled_by | bigint | c? |  |  |
| access_revoked_at | timestamp | c? |  |  |
| paid_at | timestamp | c? |  |  |
| gross_amount | decimal(12,2) | c? |  |  |
| net_amount | decimal(12,2) | c? |  |  |
| platform_amount | decimal(12,2) | kh?ng |  | default = 0 |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o payment_id: M? thanh to?n.
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o instructor_id: M? gi?ng vi?n.
o course_title: tr??ng d? li?u c?a b?ng orders.
o price: tr??ng d? li?u c?a b?ng orders.
o status: default = 'completed'.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng payments

B?ng 4.15 D? li?u payments

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| transaction_id | varchar(255) | kh?ng |  |  |
| name | varchar(255) | c? |  | T?n hi?n th? |
| email | varchar(255) | c? |  | Email ??ng nh?p, th??ng l? duy nh?t |
| phone | varchar(255) | c? |  |  |
| address | varchar(255) | c? |  |  |
| cash_delivery | varchar(255) | c? |  |  |
| total_amount | varchar(255) | c? |  |  |
| refunded_amount | decimal(12,2) | kh?ng |  | default = 0 |
| refunded_at | timestamp | c? |  |  |
| refund_reference | varchar(255) | c? |  |  |
| provider_payload | json | c? |  |  |
| provider_status | varchar(255) | c? |  |  |
| payment_type | varchar(255) | c? |  |  |
| invoice_no | varchar(255) | c? |  |  |
| order_date | varchar(255) | c? |  |  |
| order_month | varchar(255) | c? |  |  |
| order_year | varchar(255) | c? |  |  |
| status | varchar(255) | c? |  | Tr?ng th?i b?n ghi |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o transaction_id: tr??ng d? li?u c?a b?ng payments.
o name: T?n hi?n th?.
o email: Email ??ng nh?p, th??ng l? duy nh?t.
o phone: tr??ng d? li?u c?a b?ng payments.
o address: tr??ng d? li?u c?a b?ng payments.
o cash_delivery: tr??ng d? li?u c?a b?ng payments.
o total_amount: tr??ng d? li?u c?a b?ng payments.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng googles

B?ng 4.16 D? li?u googles

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| client_id | varchar(255) | kh?ng |  |  |
| secret_key | varchar(255) | kh?ng |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o client_id: tr??ng d? li?u c?a b?ng googles.
o secret_key: tr??ng d? li?u c?a b?ng googles.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng smtps

B?ng 4.17 D? li?u smtps

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| mailer | varchar(255) | c? |  |  |
| host | varchar(255) | c? |  |  |
| port | varchar(255) | c? |  |  |
| username | varchar(255) | c? |  |  |
| password | varchar(255) | c? |  | M?t kh?u ?? m? h?a |
| encryption | varchar(255) | c? |  |  |
| from_address | varchar(255) | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o mailer: tr??ng d? li?u c?a b?ng smtps.
o host: tr??ng d? li?u c?a b?ng smtps.
o port: tr??ng d? li?u c?a b?ng smtps.
o username: tr??ng d? li?u c?a b?ng smtps.
o password: M?t kh?u ?? m? h?a.
o encryption: tr??ng d? li?u c?a b?ng smtps.
o from_address: tr??ng d? li?u c?a b?ng smtps.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng partners

B?ng 4.18 D? li?u partners

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| name | text | kh?ng |  | T?n hi?n th? |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o name: T?n hi?n th?.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng site_infos

B?ng 4.19 D? li?u site_infos

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| meta_title | varchar(255) | c? |  |  |
| copyright | varchar(255) | c? |  |  |
| meta_description | varchar(255) | c? |  |  |
| logo | varchar(255) | c? |  |  |
| favicon | varchar(255) | c? |  |  |
| address | varchar(255) | c? |  |  |
| phone | varchar(255) | c? |  |  |
| mail | varchar(255) | c? |  |  |
| facebook | varchar(255) | c? |  |  |
| twitter | varchar(255) | c? |  |  |
| instagram | varchar(255) | c? |  |  |
| linkedin | varchar(255) | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o meta_title: tr??ng d? li?u c?a b?ng site_infos.
o copyright: tr??ng d? li?u c?a b?ng site_infos.
o meta_description: tr??ng d? li?u c?a b?ng site_infos.
o logo: tr??ng d? li?u c?a b?ng site_infos.
o favicon: tr??ng d? li?u c?a b?ng site_infos.
o address: tr??ng d? li?u c?a b?ng site_infos.
o phone: tr??ng d? li?u c?a b?ng site_infos.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng lecture_discussions

B?ng 4.20 D? li?u lecture_discussions

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| parent_id | bigint | c? | lecture_discussions(id) |  |
| content | text | kh?ng |  | N?i dung |
| is_approved | boolean | kh?ng |  | default = true |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o lecture_id: M? b?i gi?ng.
o user_id: M? ng??i d?ng.
o parent_id: li?n k?t t?i lecture_discussions(id).
o content: N?i dung.
o is_approved: default = true.
o created_at: Ng?y t?o.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng lecture_notes

B?ng 4.21 D? li?u lecture_notes

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| note | text | kh?ng |  |  |
| video_second | int | kh?ng |  |  |
| formatted_time | varchar(20) | kh?ng |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o lecture_id: M? b?i gi?ng.
o note: tr??ng d? li?u c?a b?ng lecture_notes.
o video_second: tr??ng d? li?u c?a b?ng lecture_notes.
o formatted_time: tr??ng d? li?u c?a b?ng lecture_notes.
o created_at: Ng?y t?o.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng quizzes

B?ng 4.22 D? li?u quizzes

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| section_id | bigint | c? | course_sections(id) | M? ch??ng h?c |
| lecture_id | bigint | kh?ng | course_lectures(id) | unique |
| title | varchar(255) | c? |  | Ti?u ?? |
| description | text | c? |  | M? t? |
| source_type | varchar(20) | kh?ng |  | default = 'manual' |
| time_limit | int | c? |  |  |
| passing_score | int | kh?ng |  | default = 0 |
| max_attempts | int | c? |  |  |
| shuffle_questions | boolean | kh?ng |  | default = false |
| show_result_immediately | boolean | kh?ng |  | default = true |
| is_active | boolean | kh?ng |  | default = true |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o section_id: M? ch??ng h?c.
o lecture_id: unique.
o title: Ti?u ??.
o description: M? t?.
o source_type: default = 'manual'.
o time_limit: tr??ng d? li?u c?a b?ng quizzes.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng quiz_questions

B?ng 4.23 D? li?u quiz_questions

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| quiz_id | bigint | kh?ng | quizzes(id) | M? b?i ki?m tra |
| question_text | text | kh?ng |  |  |
| question_type | varchar(30) | kh?ng |  | default = 'single_choice' |
| explanation | text | c? |  |  |
| points | int | kh?ng |  | default = 1 |
| sort_order | int | kh?ng |  | default = 1 |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o quiz_id: M? b?i ki?m tra.
o question_text: tr??ng d? li?u c?a b?ng quiz_questions.
o question_type: default = 'single_choice'.
o explanation: tr??ng d? li?u c?a b?ng quiz_questions.
o points: default = 1.
o sort_order: default = 1.
o created_at: Ng?y t?o.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng quiz_options

B?ng 4.24 D? li?u quiz_options

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| question_id | bigint | kh?ng | quiz_questions(id) | M? c?u h?i |
| option_text | text | kh?ng |  |  |
| is_correct | boolean | kh?ng |  | default = false |
| sort_order | int | kh?ng |  | default = 1 |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o question_id: M? c?u h?i.
o option_text: tr??ng d? li?u c?a b?ng quiz_options.
o is_correct: default = false.
o sort_order: default = 1.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng quiz_attempts

B?ng 4.25 D? li?u quiz_attempts

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| quiz_id | bigint | kh?ng | quizzes(id) | M? b?i ki?m tra |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| score | int | kh?ng |  | default = 0 |
| total_questions | int | kh?ng |  | default = 0 |
| correct_answers | int | kh?ng |  | default = 0 |
| status | varchar(20) | kh?ng |  | default = 'submitted' |
| started_at | timestamp | c? |  |  |
| submitted_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o quiz_id: M? b?i ki?m tra.
o lecture_id: M? b?i gi?ng.
o course_id: M? kh?a h?c.
o user_id: M? ng??i d?ng.
o score: default = 0.
o total_questions: default = 0.
o correct_answers: default = 0.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng quiz_attempt_answers

B?ng 4.26 D? li?u quiz_attempt_answers

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| attempt_id | bigint | kh?ng | quiz_attempts(id) | M? l??t l?m b?i |
| question_id | bigint | kh?ng | quiz_questions(id) | M? c?u h?i |
| selected_option_id | bigint | c? | quiz_options(id) |  |
| is_correct | boolean | kh?ng |  | default = false |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o attempt_id: M? l??t l?m b?i.
o question_id: M? c?u h?i.
o selected_option_id: li?n k?t t?i quiz_options(id).
o is_correct: default = false.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng instructor_requests

B?ng 4.27 D? li?u instructor_requests

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| headline | varchar(255) | c? |  |  |
| bio | text | c? |  |  |
| experience | text | c? |  |  |
| phone | varchar(255) | c? |  |  |
| status | varchar(255) | kh?ng |  | default = 'pending' |
| admin_note | text | c? |  |  |
| reviewed_by | bigint | c? | users(id) |  |
| reviewed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o user_id: M? ng??i d?ng.
o headline: tr??ng d? li?u c?a b?ng instructor_requests.
o bio: tr??ng d? li?u c?a b?ng instructor_requests.
o experience: tr??ng d? li?u c?a b?ng instructor_requests.
o phone: tr??ng d? li?u c?a b?ng instructor_requests.
o status: default = 'pending'.
o admin_note: tr??ng d? li?u c?a b?ng instructor_requests.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng refund_requests

B?ng 4.28 D? li?u refund_requests

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| order_id | bigint | kh?ng | orders(id) | M? ??n h?ng |
| payment_id | bigint | c? | payments(id) | M? thanh to?n |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| instructor_id | bigint | c? | users(id) | M? gi?ng vi?n |
| request_source | varchar(255) | kh?ng |  | default = 'user' |
| type | varchar(255) | kh?ng |  | default = 'refund' |
| status | varchar(255) | kh?ng |  | default = 'pending' |
| requested_amount | decimal(12,2) | c? |  |  |
| approved_amount | decimal(12,2) | c? |  |  |
| reason | text | c? |  |  |
| admin_note | text | c? |  |  |
| provider_ref | varchar(255) | c? |  |  |
| requested_at | timestamp | c? |  |  |
| reviewed_by | bigint | c? | users(id) |  |
| reviewed_at | timestamp | c? |  |  |
| processed_by | bigint | c? | users(id) |  |
| processed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o order_id: M? ??n h?ng.
o payment_id: M? thanh to?n.
o user_id: M? ng??i d?ng.
o instructor_id: M? gi?ng vi?n.
o request_source: default = 'user'.
o type: default = 'refund'.
o status: default = 'pending'.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng order_status_histories

B?ng 4.29 D? li?u order_status_histories

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| order_id | bigint | kh?ng | orders(id) | M? ??n h?ng |
| payment_id | bigint | c? | payments(id) | M? thanh to?n |
| from_status | varchar(255) | c? |  |  |
| to_status | varchar(255) | c? |  |  |
| from_refund_status | varchar(255) | c? |  |  |
| to_refund_status | varchar(255) | c? |  |  |
| action | varchar(255) | kh?ng |  |  |
| actor_id | bigint | c? | users(id) |  |
| actor_role | varchar(255) | c? |  |  |
| note | text | c? |  |  |
| meta_json | json | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o order_id: M? ??n h?ng.
o payment_id: M? thanh to?n.
o from_status: tr??ng d? li?u c?a b?ng order_status_histories.
o to_status: tr??ng d? li?u c?a b?ng order_status_histories.
o from_refund_status: tr??ng d? li?u c?a b?ng order_status_histories.
o to_refund_status: tr??ng d? li?u c?a b?ng order_status_histories.
o action: tr??ng d? li?u c?a b?ng order_status_histories.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng course_reviews

B?ng 4.30 D? li?u course_reviews

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|

Trong ??:

- B?ng admin_audit_logs

B?ng 4.31 D? li?u admin_audit_logs

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| admin_id | bigint | kh?ng | users(id) |  |
| action | varchar(255) | kh?ng |  |  |
| target_type | varchar(255) | kh?ng |  |  |
| target_id | bigint | c? |  |  |
| note | text | c? |  |  |
| old_values_json | json | c? |  |  |
| new_values_json | json | c? |  |  |
| context_json | json | c? |  |  |
| ip_address | varchar(45) | c? |  |  |
| user_agent | text | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o admin_id: li?n k?t t?i users(id).
o action: tr??ng d? li?u c?a b?ng admin_audit_logs.
o target_type: tr??ng d? li?u c?a b?ng admin_audit_logs.
o target_id: tr??ng d? li?u c?a b?ng admin_audit_logs.
o note: tr??ng d? li?u c?a b?ng admin_audit_logs.
o old_values_json: tr??ng d? li?u c?a b?ng admin_audit_logs.
o new_values_json: tr??ng d? li?u c?a b?ng admin_audit_logs.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng content_reports

B?ng 4.32 D? li?u content_reports

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| reporter_id | bigint | kh?ng | users(id) |  |
| reported_user_id | bigint | kh?ng | users(id) |  |
| reportable_type | varchar(255) | kh?ng |  |  |
| reportable_id | bigint | kh?ng |  |  |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| reason_code | varchar(255) | kh?ng |  |  |
| description | text | c? |  | M? t? |
| status | varchar(255) | kh?ng |  | default = 'pending' |
| resolution_action | varchar(255) | c? |  |  |
| resolution_note | text | c? |  |  |
| content_snapshot | json | c? |  |  |
| reviewed_by | bigint | kh?ng | users(id) |  |
| reviewed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |
| policy_id | bigint | c? | moderation_policies(id) |  |
| action_template_id | bigint | c? | moderation_action_templates(id) |  |

Trong ??:
o id: Kh?a ch?nh.
o reporter_id: li?n k?t t?i users(id).
o reported_user_id: li?n k?t t?i users(id).
o reportable_type: tr??ng d? li?u c?a b?ng content_reports.
o reportable_id: tr??ng d? li?u c?a b?ng content_reports.
o course_id: M? kh?a h?c.
o lecture_id: M? b?i gi?ng.
o reason_code: tr??ng d? li?u c?a b?ng content_reports.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng enrollments

B?ng 4.33 D? li?u enrollments

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| order_id | bigint | c? | orders(id) | M? ??n h?ng |
| source | varchar(255) | kh?ng |  | default = 'order' |
| status | varchar(255) | kh?ng |  | default = 'active' |
| access_granted_at | timestamp | c? |  |  |
| access_expires_at | timestamp | c? |  |  |
| last_lecture_id | bigint | c? | course_lectures(id) |  |
| last_accessed_at | timestamp | c? |  |  |
| completed_at | timestamp | c? |  |  |
| revoked_at | timestamp | c? |  |  |
| revoked_reason | text | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o order_id: M? ??n h?ng.
o source: default = 'order'.
o status: default = 'active'.
o access_granted_at: tr??ng d? li?u c?a b?ng enrollments.
o access_expires_at: tr??ng d? li?u c?a b?ng enrollments.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng lesson_progress

B?ng 4.34 D? li?u lesson_progress

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| enrollment_id | bigint | kh?ng | enrollments(id) |  |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| section_id | bigint | c? | course_sections(id) | M? ch??ng h?c |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| status | varchar(255) | kh?ng |  | default = 'not_started' |
| progress_percent | int | kh?ng |  | default = 0 |
| watch_seconds | int | kh?ng |  | default = 0 |
| started_at | timestamp | c? |  |  |
| last_watched_at | timestamp | c? |  |  |
| completed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o enrollment_id: li?n k?t t?i enrollments(id).
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o section_id: M? ch??ng h?c.
o lecture_id: M? b?i gi?ng.
o status: default = 'not_started'.
o progress_percent: default = 0.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng course_progress

B?ng 4.35 D? li?u course_progress

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| enrollment_id | bigint | kh?ng | enrollments(id) |  |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| total_lectures | int | kh?ng |  | default = 0 |
| completed_lectures | int | kh?ng |  | default = 0 |
| completion_percent | int | kh?ng |  | default = 0 |
| last_lecture_id | bigint | c? | course_lectures(id) |  |
| last_activity_at | timestamp | c? |  |  |
| completed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o enrollment_id: li?n k?t t?i enrollments(id).
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o total_lectures: default = 0.
o completed_lectures: default = 0.
o completion_percent: default = 0.
o last_lecture_id: li?n k?t t?i course_lectures(id).
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng course_quality_checks

B?ng 4.36 D? li?u course_quality_checks

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| check_key | varchar(255) | kh?ng |  |  |
| status | varchar(255) | kh?ng |  | default = 'fail' |
| message | text | c? |  |  |
| reviewed_by | bigint | c? | users(id) |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o check_key: tr??ng d? li?u c?a b?ng course_quality_checks.
o status: default = 'fail'.
o message: tr??ng d? li?u c?a b?ng course_quality_checks.
o reviewed_by: li?n k?t t?i users(id).
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng moderation_policies

B?ng 4.37 D? li?u moderation_policies

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| code | varchar(255) | kh?ng |  | unique |
| name | varchar(255) | kh?ng |  | T?n hi?n th? |
| target_type | varchar(255) | c? |  |  |
| description | text | c? |  | M? t? |
| is_active | boolean | kh?ng |  | default = true |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o code: unique.
o name: T?n hi?n th?.
o target_type: tr??ng d? li?u c?a b?ng moderation_policies.
o description: M? t?.
o is_active: default = true.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng moderation_action_templates

B?ng 4.38 D? li?u moderation_action_templates

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| code | varchar(255) | kh?ng |  | unique |
| name | varchar(255) | kh?ng |  | T?n hi?n th? |
| target_type | varchar(255) | c? |  |  |
| default_note | text | c? |  |  |
| requires_reason | boolean | kh?ng |  | default = true |
| is_active | boolean | kh?ng |  | default = true |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o code: unique.
o name: T?n hi?n th?.
o target_type: tr??ng d? li?u c?a b?ng moderation_action_templates.
o default_note: tr??ng d? li?u c?a b?ng moderation_action_templates.
o requires_reason: default = true.
o is_active: default = true.
o created_at: Ng?y t?o.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng instructor_risk_scores

B?ng 4.39 D? li?u instructor_risk_scores

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| instructor_id | bigint | kh?ng | users(id) | unique |
| risk_score | int | kh?ng |  | default = 0 |
| confirmed_reports_count | int | kh?ng |  | default = 0 |
| refund_requests_count | int | kh?ng |  | default = 0 |
| rejected_courses_count | int | kh?ng |  | default = 0 |
| warnings_count | int | kh?ng |  | default = 0 |
| calculated_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o instructor_id: unique.
o risk_score: default = 0.
o confirmed_reports_count: default = 0.
o refund_requests_count: default = 0.
o rejected_courses_count: default = 0.
o warnings_count: default = 0.
o calculated_at: tr??ng d? li?u c?a b?ng instructor_risk_scores.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng ai_chat_sessions

B?ng 4.40 D? li?u ai_chat_sessions

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| user_id | bigint | kh?ng | users(id) | M? ng??i d?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| title | varchar(255) | c? |  | Ti?u ?? |
| status | varchar(255) | kh?ng |  | default = 'active' |
| last_activity_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o user_id: M? ng??i d?ng.
o course_id: M? kh?a h?c.
o lecture_id: M? b?i gi?ng.
o title: Ti?u ??.
o status: default = 'active'.
o last_activity_at: tr??ng d? li?u c?a b?ng ai_chat_sessions.
o created_at: Ng?y t?o.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng ai_chat_messages

B?ng 4.41 D? li?u ai_chat_messages

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| session_id | bigint | kh?ng | ai_chat_sessions(id) | M? phi?n chat |
| user_id | bigint | c? | users(id) | M? ng??i d?ng |
| role | varchar(20) | kh?ng |  | Vai tr? ng??i d?ng |
| content | text | kh?ng |  | N?i dung |
| provider | varchar(255) | c? |  |  |
| model | varchar(255) | c? |  |  |
| prompt_tokens | int | c? |  |  |
| completion_tokens | int | c? |  |  |
| latency_ms | int | c? |  |  |
| meta_json | json | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o session_id: M? phi?n chat.
o user_id: M? ng??i d?ng.
o role: Vai tr? ng??i d?ng.
o content: N?i dung.
o provider: tr??ng d? li?u c?a b?ng ai_chat_messages.
o model: tr??ng d? li?u c?a b?ng ai_chat_messages.
o prompt_tokens: tr??ng d? li?u c?a b?ng ai_chat_messages.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng ai_documents

B?ng 4.42 D? li?u ai_documents

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| lecture_id | bigint | c? | course_lectures(id) | M? b?i gi?ng |
| uploaded_by | bigint | kh?ng | users(id) |  |
| title | varchar(255) | kh?ng |  | Ti?u ?? |
| source_type | varchar(50) | kh?ng |  | default = 'manual_upload' |
| file_name | varchar(255) | c? |  |  |
| mime_type | varchar(255) | c? |  |  |
| storage_disk | varchar(255) | c? |  |  |
| storage_path | varchar(255) | c? |  |  |
| extracted_text | text | c? |  |  |
| language | varchar(10) | kh?ng |  | default = 'vi' |
| index_status | varchar(20) | kh?ng |  | default = 'pending' |
| index_error | text | c? |  |  |
| indexed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o course_id: M? kh?a h?c.
o lecture_id: M? b?i gi?ng.
o uploaded_by: li?n k?t t?i users(id).
o title: Ti?u ??.
o source_type: default = 'manual_upload'.
o file_name: tr??ng d? li?u c?a b?ng ai_documents.
o mime_type: tr??ng d? li?u c?a b?ng ai_documents.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng ai_document_chunks

B?ng 4.43 D? li?u ai_document_chunks

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| document_id | bigint | kh?ng | ai_documents(id) | M? t?i li?u |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| lecture_id | bigint | c? | course_lectures(id) | M? b?i gi?ng |
| chunk_index | int | kh?ng |  |  |
| content | text | kh?ng |  | N?i dung |
| content_length | int | kh?ng |  | default = 0 |
| meta_json | json | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |
| embedding | vector(768) | c? |  |  |
| embedding_provider | varchar(100) | c? |  |  |
| embedding_model | varchar(150) | c? |  |  |
| embedding_status | varchar(30) | kh?ng |  | default = 'pending' |
| embedding_error | text | c? |  |  |
| external_vector_id | varchar(255) | c? |  |  |

Trong ??:
o id: Kh?a ch?nh.
o document_id: M? t?i li?u.
o course_id: M? kh?a h?c.
o lecture_id: M? b?i gi?ng.
o chunk_index: tr??ng d? li?u c?a b?ng ai_document_chunks.
o content: N?i dung.
o content_length: default = 0.
o meta_json: tr??ng d? li?u c?a b?ng ai_document_chunks.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng ai_message_citations

B?ng 4.44 D? li?u ai_message_citations

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| message_id | bigint | kh?ng | ai_chat_messages(id) |  |
| document_id | bigint | kh?ng | ai_documents(id) | M? t?i li?u |
| chunk_id | bigint | kh?ng | ai_document_chunks(id) | M? ?o?n t?i li?u |
| rank | int | kh?ng |  | default = 1 |
| score | decimal(8,4) | c? |  |  |
| snippet | text | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o message_id: li?n k?t t?i ai_chat_messages(id).
o document_id: M? t?i li?u.
o chunk_id: M? ?o?n t?i li?u.
o rank: default = 1.
o score: tr??ng d? li?u c?a b?ng ai_message_citations.
o snippet: tr??ng d? li?u c?a b?ng ai_message_citations.
o created_at: Ng?y t?o.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng gemini_settings

B?ng 4.45 D? li?u gemini_settings

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| api_key | text | c? |  |  |
| model_name | varchar(255) | kh?ng |  | default = 'gemini-1.5-flash' |
| timeout_seconds | int | kh?ng |  | default = 30 |
| temperature | decimal(3,2) | kh?ng |  | default = 0.20 |
| max_output_tokens | int | kh?ng |  | default = 1024 |
| is_enabled | boolean | kh?ng |  | default = true |
| updated_by | bigint | c? | users(id) |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |
| base_url | varchar(255) | c? |  |  |

Trong ??:
o id: Kh?a ch?nh.
o api_key: tr??ng d? li?u c?a b?ng gemini_settings.
o model_name: default = 'gemini-1.5-flash'.
o timeout_seconds: default = 30.
o temperature: default = 0.20.
o max_output_tokens: default = 1024.
o is_enabled: default = true.
o updated_by: li?n k?t t?i users(id).
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng concepts

B?ng 4.46 D? li?u concepts

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| name | varchar(255) | kh?ng |  | unique |
| description | text | c? |  | M? t? |
| synonyms_json | json | c? |  |  |
| is_active | boolean | kh?ng |  | default = true |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o name: unique.
o description: M? t?.
o synonyms_json: tr??ng d? li?u c?a b?ng concepts.
o is_active: default = true.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng lesson_concepts

B?ng 4.47 D? li?u lesson_concepts

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| concept_id | bigint | kh?ng | concepts(id) | M? kh?i ni?m |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o lecture_id: M? b?i gi?ng.
o concept_id: M? kh?i ni?m.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng document_concepts

B?ng 4.48 D? li?u document_concepts

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| document_id | bigint | kh?ng | ai_documents(id) | M? t?i li?u |
| concept_id | bigint | kh?ng | concepts(id) | M? kh?i ni?m |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o document_id: M? t?i li?u.
o concept_id: M? kh?i ni?m.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng transcript_jobs

B?ng 4.49 D? li?u transcript_jobs

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| lecture_id | bigint | kh?ng | course_lectures(id) | M? b?i gi?ng |
| course_id | bigint | kh?ng | courses(id) | M? kh?a h?c |
| requested_by | bigint | kh?ng | users(id) |  |
| document_id | bigint | c? | ai_documents(id) | M? t?i li?u |
| status | varchar(30) | kh?ng |  | default = 'queued' |
| progress | tinyint | kh?ng |  | default = 0 |
| error_message | text | c? |  |  |
| request_payload | json | c? |  |  |
| response_payload | json | c? |  |  |
| started_at | timestamp | c? |  |  |
| finished_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o lecture_id: M? b?i gi?ng.
o course_id: M? kh?a h?c.
o requested_by: li?n k?t t?i users(id).
o document_id: M? t?i li?u.
o status: default = 'queued'.
o progress: default = 0.
o error_message: tr??ng d? li?u c?a b?ng transcript_jobs.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng payout_requests

B?ng 4.50 D? li?u payout_requests

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| instructor_id | bigint | kh?ng | users(id) | M? gi?ng vi?n |
| amount | decimal(12,2) | kh?ng |  |  |
| bank_name | varchar(255) | kh?ng |  |  |
| account_number | varchar(255) | kh?ng |  |  |
| account_name | varchar(255) | kh?ng |  |  |
| status | varchar(255) | kh?ng |  | default = 'pending' |
| transaction_reference | varchar(255) | c? |  |  |
| admin_note | text | c? |  |  |
| processed_at | timestamp | c? |  |  |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o instructor_id: M? gi?ng vi?n.
o amount: tr??ng d? li?u c?a b?ng payout_requests.
o bank_name: tr??ng d? li?u c?a b?ng payout_requests.
o account_number: tr??ng d? li?u c?a b?ng payout_requests.
o account_name: tr??ng d? li?u c?a b?ng payout_requests.
o status: default = 'pending'.
o transaction_reference: tr??ng d? li?u c?a b?ng payout_requests.
o C?c tr??ng c?n l?i d?ng ?? l?u th?ng tin b? sung, tr?ng th?i x? l? ho?c th?i ?i?m t?o/c?p nh?t b?n ghi.

- B?ng conversations

B?ng 4.51 D? li?u conversations

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| student_id | bigint | kh?ng | users(id) |  |
| instructor_id | bigint | kh?ng | users(id) | M? gi?ng vi?n |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o student_id: li?n k?t t?i users(id).
o instructor_id: M? gi?ng vi?n.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

- B?ng messages

B?ng 4.52 D? li?u messages

| T?n c?t | Ki?u d? li?u | Null | Li?n k?t t?i | Ghi ch? |
|---|---|---|---|---|
| id | bigint | kh?ng |  | Kh?a ch?nh |
| conversation_id | bigint | kh?ng | conversations(id) |  |
| sender_id | bigint | kh?ng | users(id) |  |
| message | text | kh?ng |  |  |
| is_read | boolean | kh?ng |  | default = false |
| created_at | timestamp | c? |  | Ng?y t?o |
| updated_at | timestamp | c? |  | Ng?y c?p nh?t |

Trong ??:
o id: Kh?a ch?nh.
o conversation_id: li?n k?t t?i conversations(id).
o sender_id: li?n k?t t?i users(id).
o message: tr??ng d? li?u c?a b?ng messages.
o is_read: default = false.
o created_at: Ng?y t?o.
o updated_at: Ng?y c?p nh?t.

