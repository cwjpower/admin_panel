// lib/models/volume.dart
class Volume {
  final int volumeId;
  final int volumeNumber;
  final String volumeTitle;
  final String? coverImage;
  final int normalPrice;
  final String price;
  final int discountRate;
  final bool isFree;
  final int totalPages;
  final String? publishDate;
  final String status;

  // 시리즈 정보 (상세 조회 시)
  final int? seriesId;
  final String? seriesName;
  final String? author;
  final String? category;

  Volume({
    required this.volumeId,
    required this.volumeNumber,
    required this.volumeTitle,
    this.coverImage,
    required this.normalPrice,
    required this.price,
    required this.discountRate,
    required this.isFree,
    required this.totalPages,
    this.publishDate,
    required this.status,
    this.seriesId,
    this.seriesName,
    this.author,
    this.category,
  });

  factory Volume.fromJson(Map<String, dynamic> json) {
    return Volume(
      volumeId: int.parse(json['volume_id'].toString()),
      volumeNumber: int.parse(json['volume_number'].toString()),
      volumeTitle: json['volume_title'] ?? '',
      coverImage: json['cover_image'],
      normalPrice: int.parse(json['normal_price'].toString()),
      price: json['price'].toString(),
      discountRate: int.parse(json['discount_rate'].toString()),
      isFree: json['is_free'] == 1 || json['is_free'] == true,
      totalPages: int.parse(json['total_pages']?.toString() ?? '0'),
      publishDate: json['publish_date'],
      status: json['status'] ?? 'draft',
      seriesId: json['series_id'] != null
          ? int.parse(json['series_id'].toString())
          : null,
      seriesName: json['series_name'],
      author: json['author'],
      category: json['category'],
    );
  }

  // 가격 계산
  int get finalPrice {
    return normalPrice - (normalPrice * discountRate ~/ 100);
  }

  String get formattedPrice {
    return '₩${finalPrice.toString().replaceAllMapped(
        RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
            (Match m) => '${m[1]},'
    )}';
  }

  String get formattedNormalPrice {
    return '₩${normalPrice.toString().replaceAllMapped(
        RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
            (Match m) => '${m[1]},'
    )}';
  }
}