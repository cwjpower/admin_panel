class News {
  final int id;
  final String title;
  final String? content;
  final String? thumbnail;
  final String? category; // MARVEL, DC, IMAGE 등
  final DateTime? createdAt;
  final int? viewCount;
  final int? likeCount;

  News({
    required this.id,
    required this.title,
    this.content,
    this.thumbnail,
    this.category,
    this.createdAt,
    this.viewCount,
    this.likeCount,
  });

  factory News.fromJson(Map<String, dynamic> json) {
    return News(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      content: json['content'],
      thumbnail: json['thumbnail'],
      category: json['category'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      viewCount: json['view_count'],
      likeCount: json['like_count'],
    );
  }
}