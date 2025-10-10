SELECT
b.ID,
b.book_title,
b.author,
b.publisher, b.cover_img, i.epub_url, m.meta_value AS epub,
m2.meta_value AS total_page,     m3.meta_value AS chat_url
FROM
bt_order AS o INNER JOIN bt_order_item AS i INNER JOIN bt_books AS b LEFT JOIN bt_books_meta AS m ON b.ID = m.book_id
AND m.meta_key = 'lps_book_epub_file' LEFT JOIN bt_books_meta AS m2 ON b.ID = m2.book_id
AND m2.meta_key = 'lps_book_total_page' LEFT JOIN bt_books_meta AS m3 ON b.ID = m3.book_id
AND m3.meta_key = 'lps_sendbird_chat_url'
WHERE o.user_id = '83' AND o.order_id = i.order_id AND i.book_id = b.ID
ORDER BY i.book_down_dt DESC ;
