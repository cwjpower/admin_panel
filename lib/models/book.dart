// lib/models/book.dart

class Book {
  final String id;
  final String title;
  final String author;
  final String coverImage;
  final double rating;
  final int reviewCount;
  final String price;
  final String? description;
  final String publisher;
  final bool isFree;
  final bool isNew;
  final String? category;  // 추가
  final String? publishDate;  // 추가
  final String? isbn;
  final int? pageCount;
  final String? language;
  final List<String>? genres;
  final String? series;
  final int? seriesNumber;
  final bool? isPurchased;
  final bool? isWishlisted;
  final double? readProgress;

  Book({
    required this.id,
    required this.title,
    required this.author,
    required this.coverImage,
    required this.rating,
    required this.reviewCount,
    required this.price,
    this.description,
    required this.publisher,
    this.isFree = false,
    this.isNew = false,
    this.category,
    this.publishDate,
    this.isbn,
    this.pageCount,
    this.language,
    this.genres,
    this.series,
    this.seriesNumber,
    this.isPurchased,
    this.isWishlisted,
    this.readProgress,
  });

  // JSON 변환 메서드 - API 응답에 맞춤
  factory Book.fromJson(Map<String, dynamic> json) {
    return Book(
      id: json['id']?.toString() ?? json['book_id']?.toString() ?? '',
      title: json['title'] ?? '',
      author: json['author'] ?? '',
      coverImage: json['cover_image'] ?? json['thumbnail'] ?? '',
      rating: json['rating'] != null ?
      (json['rating'] is String ? double.parse(json['rating']) : json['rating'].toDouble()) : 4.0,
      reviewCount: json['review_count'] ?? json['view_count'] ?? 0,
      price: json['price']?.toString() ?? '0',
      description: json['description'],
      publisher: json['publisher'] ?? '',
      isFree: json['is_free'] ?? (json['price'] == '0' || json['price'] == 0 || json['price'] == '0.00'),
      isNew: json['is_new'] ?? false,
      category: json['category'],
      publishDate: json['publish_date'],
      isbn: json['isbn'],
      pageCount: json['page_count'],
      language: json['language'],
      genres: json['genres'] != null
          ? List<String>.from(json['genres'])
          : null,
      series: json['series'],
      seriesNumber: json['series_number'],
      isPurchased: json['is_purchased'],
      isWishlisted: json['is_wishlisted'],
      readProgress: json['read_progress']?.toDouble(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'author': author,
      'cover_image': coverImage,
      'rating': rating,
      'review_count': reviewCount,
      'price': price,
      'description': description,
      'publisher': publisher,
      'is_free': isFree,
      'is_new': isNew,
      'category': category,
      'publish_date': publishDate,
      'isbn': isbn,
      'page_count': pageCount,
      'language': language,
      'genres': genres,
      'series': series,
      'series_number': seriesNumber,
      'is_purchased': isPurchased,
      'is_wishlisted': isWishlisted,
      'read_progress': readProgress,
    };
  }

  // 가격 표시 포맷
  String getFormattedPrice() {
    if (isFree) {
      return 'FREE';
    }
    // 원화 표시 (price가 "15000.00" 형태)
    final priceNum = double.tryParse(price) ?? 0;
    if (priceNum >= 1000) {
      // 한국 원화
      return '₩${priceNum.toStringAsFixed(0)}';
    } else {
      // 달러
      return '\$${price}';
    }
  }

  // 별점 표시 (5점 만점)
  int getStarCount() {
    return rating.floor();
  }

  // 반별 포함 여부
  bool hasHalfStar() {
    return rating - rating.floor() >= 0.5;
  }

  // 진행률 퍼센트 표시
  String getProgressPercentage() {
    if (readProgress == null) return '0%';
    return '${(readProgress! * 100).toInt()}%';
  }

  // 시리즈 정보 표시
  String? getSeriesInfo() {
    if (series == null) return null;
    if (seriesNumber != null) {
      return '$series #$seriesNumber';
    }
    return series;
  }

  // 책 상태 라벨
  List<String> getStatusLabels() {
    List<String> labels = [];
    if (isNew) labels.add('NEW');
    if (isFree) labels.add('FREE');
    if (isPurchased == true) labels.add('PURCHASED');
    return labels;
  }

  // 카테고리/출판사별 색상
  String getPublisherColor() {
    // category가 있으면 category 사용, 없으면 publisher 사용
    final brandName = (category ?? publisher).toUpperCase();
    switch (brandName) {
      case 'MARVEL':
        return '#FF0000';  // Red
      case 'DC':
        return '#0000FF';  // Blue
      case 'IMAGE':
        return '#800080';  // Purple
      case 'DARK HORSE':
        return '#FFA500';  // Orange
      default:
        return '#333333';  // Dark Gray
    }
  }
}