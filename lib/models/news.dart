class News {
  final int id;
  final String title;
  final String content;
  final String category;
  final String imageUrl;
  final DateTime date;

  News({
    required this.id,
    required this.title,
    required this.content,
    required this.category,
    required this.imageUrl,
    required this.date,
  });

  factory News.fromJson(Map<String, dynamic> json) {
    return News(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      content: json['content'] ?? '',
      category: json['category'] ?? 'GENERAL',
      imageUrl: json['image_url'] ?? 'https://via.placeholder.com/300x150',
      date: json['date'] != null ? DateTime.parse(json['date']) : DateTime.now(),
    );
  }
}