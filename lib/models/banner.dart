// lib/models/banner.dart

class AppBanner {
  final String id;
  final String imageUrl;
  final String title;
  final String? link;

  AppBanner({
    required this.id,
    required this.imageUrl,
    required this.title,
    this.link,
  });

  // JSON 변환 메서드 추가 (API 연동 시 필요)
  factory AppBanner.fromJson(Map<String, dynamic> json) {
    return AppBanner(
      id: json['id'] ?? '',
      imageUrl: json['image_url'] ?? '',
      title: json['title'] ?? '',
      link: json['link'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'image_url': imageUrl,
      'title': title,
      'link': link,
    };
  }
}