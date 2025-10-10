class Book {
  final String id;
  final String title;
  final String author;
  final String publisher;
  final String price;
  final String coverImage;
  final String? description;  // nullable
  final double rating;
  final int reviewCount;

  Book({
    required this.id,
    required this.title,
    required this.author,
    required this.publisher,
    required this.price,
    required this.coverImage,
    this.description,  // nullable - required 제거
    required this.rating,
    required this.reviewCount,
  });

  factory Book.fromJson(Map<String, dynamic> json) {
    // price 처리 - int로 오면 String으로 변환
    String priceStr = '0';
    if (json['price'] != null) {
      if (json['price'] is int || json['price'] is double) {
        priceStr = json['price'].toString();
      } else {
        priceStr = json['price'] as String;
      }
    }

    // rating 처리 - double로 변환
    double ratingValue = 4.5;
    if (json['rating'] != null) {
      if (json['rating'] is int) {
        ratingValue = (json['rating'] as int).toDouble();
      } else if (json['rating'] is double) {
        ratingValue = json['rating'] as double;
      } else if (json['rating'] is String) {
        ratingValue = double.tryParse(json['rating']) ?? 4.5;
      }
    }

    return Book(
      id: json['id']?.toString() ?? '0',
      title: json['title'] ?? '제목 없음',
      author: json['author'] ?? '작가 미상',
      publisher: json['publisher'] ?? '',
      price: priceStr,
      coverImage: json['cover_image'] ?? 'https://via.placeholder.com/150x200',
      description: json['description'],  // nullable - 그대로
      rating: ratingValue,
      reviewCount: json['review_count'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'author': author,
      'publisher': publisher,
      'price': price,
      'cover_image': coverImage,
      'description': description,
      'rating': rating,
      'review_count': reviewCount,
    };
  }
}