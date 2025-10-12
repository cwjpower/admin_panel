class BannerItem {
  final int id;
  final String title;
  final String? description;
  final String? imageUrl;
  final String? linkUrl;
  final int order;

  BannerItem({
    required this.id,
    required this.title,
    this.description,
    this.imageUrl,
    this.linkUrl,
    required this.order,
  });

  factory BannerItem.fromJson(Map<String, dynamic> json) {
    return BannerItem(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'],
      imageUrl: json['image_url'],
      linkUrl: json['link_url'],
      order: json['order'] ?? 0,
    );
  }
}