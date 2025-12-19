INSERT INTO `auto_processes` (`auto_process_id`, `auto_process_name`, `action_type`, `action_column_name`, `action_value`, `condition_match_type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, '6,999円以下はネコポス', 'shipping_method_update', 'shipping_method_id', '1', 'all', 1, 1, '2025-12-08 22:09:36', '2025-12-08 22:09:36'),
(2, '6,999円以下かつクリアファイル有りは佐川', 'shipping_method_update', 'shipping_method_id', '4', 'all', 1, 2, '2025-12-08 22:10:31', '2025-12-08 22:12:28'),
(3, '6,999円以下かつトートバック有りは佐川', 'shipping_method_update', 'shipping_method_id', '4', 'all', 1, 3, '2025-12-08 22:11:07', '2025-12-08 22:12:35'),
(4, '6,999円以下かつTシャツ有りは佐川', 'shipping_method_update', 'shipping_method_id', '4', 'all', 1, 4, '2025-12-08 22:11:27', '2025-12-08 22:12:41'),
(5, '7,000円以上は佐川', 'shipping_method_update', 'shipping_method_id', '4', 'all', 1, 5, '2025-12-08 22:12:16', '2025-12-08 22:12:16'),
(6, '佐川急便の沖縄県はヤマト宅急便', 'shipping_method_update', 'shipping_method_id', '3', 'all', 1, 6, '2025-12-10 05:07:49', '2025-12-10 05:08:22');

INSERT INTO `auto_process_conditions` (`auto_process_condition_id`, `auto_process_id`, `column_name`, `operator`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'payment_amount', '<=', '6999', '2025-12-08 22:12:58', '2025-12-08 22:12:58'),
(2, 2, 'payment_amount', '<=', '6999', '2025-12-08 22:13:24', '2025-12-08 22:13:24'),
(3, 2, 'item_category_2', '=', 'クリアファイル', '2025-12-08 22:13:24', '2025-12-08 22:13:24'),
(4, 3, 'payment_amount', '<=', '6999', '2025-12-08 22:13:44', '2025-12-08 22:13:44'),
(5, 3, 'item_category_2', '=', 'トートバック', '2025-12-08 22:13:44', '2025-12-08 22:13:44'),
(6, 4, 'payment_amount', '<=', '6999', '2025-12-08 22:14:04', '2025-12-08 22:14:04'),
(7, 4, 'item_category_2', '=', 'Tシャツ', '2025-12-08 22:14:04', '2025-12-08 22:14:04'),
(8, 5, 'payment_amount', '>=', '7000', '2025-12-08 22:14:15', '2025-12-08 22:14:15'),
(10, 6, 'shipping_method_id', '=', '4', '2025-12-10 05:08:58', '2025-12-10 05:08:58'),
(11, 6, 'ship_province_name', '=', '沖縄県', '2025-12-10 05:08:58', '2025-12-10 05:08:58');