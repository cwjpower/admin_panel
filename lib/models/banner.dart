class Banner {
  final int id;
  final String title;
  final String image;
  final String link;
  final int order;

  Banner({
    required this.id,
    required this.title,
    required this.image,
    required this.link,
    required this.order,
  });

  factory Banner.fromJson(Map<String, dynamic> json) {
    return Banner(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      image: json['image'] ?? 'https://via.placeholder.com/800x300',
      link: json['link'] ?? '',
      order: json['order'] ?? 0,
    );
  }
}