class Review {
  final int id;
  final int bookId;
  final int userId;
  final String userName;
  final String? userProfileImage;
  final double rating;
  final String content;
  final DateTime createdAt;
  final int likeCount;

  Review({
    required this.id,
    required this.bookId,
    required this.userId,
    required this.userName,
    this.userProfileImage,
    required this.rating,
    required this.content,
    required this.createdAt,
    this.likeCount = 0,
  });

  factory Review.fromJson(Map<String, dynamic> json) {
    return Review(
      id: json['id'] ?? 0,
      bookId: json['book_id'] ?? 0,
      userId: json['user_id'] ?? 0,
      userName: json['user_name'] ?? '',
      userProfileImage: json['user_profile_image'],
      rating: (json['rating'] ?? 0).toDouble(),
      content: json['content'] ?? '',
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : DateTime.now(),
      likeCount: json['like_count'] ?? 0,
    );
  }
}