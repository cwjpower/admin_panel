class AppBanner {
  final int bannerId;
  final String title;
  final String linkUrl;
  final String target;
  final String status;
  final String imageUrl;
  final int displayOrder;
  final String? startDate;
  final String? endDate;
  final String createdAt;
  final int userId;
  final int isActive;

  AppBanner({
    required this.bannerId,
    required this.title,
    required this.linkUrl,
    required this.target,
    required this.status,
    required this.imageUrl,
    required this.displayOrder,
    this.startDate,
    this.endDate,
    required this.createdAt,
    required this.userId,
    required this.isActive,
  });

  // API 응답을 모델로 변환
  factory AppBanner.fromJson(Map<String, dynamic> json) {
    return AppBanner(
      bannerId: int.parse(json['banner_id'].toString()),
      title: json['title'] ?? '',
      linkUrl: json['link_url'] ?? '',
      target: json['target'] ?? '_self',
      status: json['status'] ?? 'show',
      imageUrl: json['image_url'] ?? '',
      displayOrder: int.parse(json['display_order'].toString()),
      startDate: json['start_date'],
      endDate: json['end_date'],
      createdAt: json['created_at'] ?? '',
      userId: int.parse(json['user_id'].toString()),
      isActive: int.parse(json['is_active'].toString()),
    );
  }

  // 전체 이미지 URL 생성
  String getFullImageUrl(String baseUrl) {
    if (imageUrl.startsWith('http')) {
      return imageUrl;
    }
    return '$baseUrl$imageUrl';
  }
}