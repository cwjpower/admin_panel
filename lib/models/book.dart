class Book {
  final int id;
  final String title;
  final String? author;
  final String? publisher;
  final String? thumbnail;
  final String? category;
  final double? rating;
  final int? price;
  final String? description;
  final DateTime? publishDate;
  final int? pageCount;
  final String? isbn;
  final int? reviewCount;
  final int? stockCount;
  final bool? isBestseller;
  final bool? isNew;

  Book({
    required this.id,
    required this.title,
    this.author,
    this.publisher,
    this.thumbnail,
    this.category,
    this.rating,
    this.price,
    this.description,
    this.publishDate,
    this.pageCount,
    this.isbn,
    this.reviewCount,
    this.stockCount,
    this.isBestseller,
    this.isNew,
  });

  factory Book.fromJson(Map<String, dynamic> json) {
    return Book(
      id: json['book_id'] ?? json['id'] ?? 0,  // ← book_id도 지원!
      title: json['title'] ?? '',
      author: json['author'],
      publisher: json['publisher'],
      thumbnail: json['thumbnail'],
      category: json['category'],
      rating: json['rating']?.toDouble(),
      price: json['price'] is String
          ? int.tryParse(json['price'])
          : json['price'],  // ← String도 처리
      description: json['description'],
      publishDate: json['publish_date'] != null
          ? DateTime.tryParse(json['publish_date'])
          : null,
      pageCount: json['page_count'],
      isbn: json['isbn'],
      reviewCount: json['review_count'],
      stockCount: json['stock_count'],
      isBestseller: json['is_bestseller'],
      isNew: json['is_new'],
    );
  }
}