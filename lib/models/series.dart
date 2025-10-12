// lib/models/series.dart
class Series {
  final int seriesId;
  final String seriesName;
  final String? seriesNameEn;
  final String author;
  final String? authorIntro;
  final String category;
  final String? comicsBrand;
  final String? description;
  final String? publisherReview;
  final String? coverImage;
  final String status;
  final int totalVolumes;
  final String? isbn;
  final String? publishedDate;
  final String? publisherName;
  final int? availableVolumes;
  final String? minPrice;
  final int? maxDiscount;

  Series({
    required this.seriesId,
    required this.seriesName,
    this.seriesNameEn,
    required this.author,
    this.authorIntro,
    required this.category,
    this.comicsBrand,
    this.description,
    this.publisherReview,
    this.coverImage,
    required this.status,
    required this.totalVolumes,
    this.isbn,
    this.publishedDate,
    this.publisherName,
    this.availableVolumes,
    this.minPrice,
    this.maxDiscount,
  });

  factory Series.fromJson(Map<String, dynamic> json) {
    return Series(
      seriesId: int.parse(json['series_id'].toString()),
      seriesName: json['series_name'] ?? '',
      seriesNameEn: json['series_name_en'],
      author: json['author'] ?? '',
      authorIntro: json['author_intro'],
      category: json['category'] ?? '',
      comicsBrand: json['comics_brand'],
      description: json['description'],
      publisherReview: json['publisher_review'],
      coverImage: json['cover_image'],
      status: json['status'] ?? 'ongoing',
      totalVolumes: int.parse(json['total_volumes'].toString()),
      isbn: json['isbn'],
      publishedDate: json['published_date'],
      publisherName: json['publisher_name'],
      availableVolumes: json['available_volumes'] != null
          ? int.parse(json['available_volumes'].toString())
          : null,
      minPrice: json['min_price']?.toString(),
      maxDiscount: json['max_discount'] != null
          ? int.parse(json['max_discount'].toString())
          : null,
    );
  }
}