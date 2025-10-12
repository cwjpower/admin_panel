class User {
  final int id;
  final String email;
  final String nickname;
  final String? profileImage;
  final DateTime createdAt;

  User({
    required this.id,
    required this.email,
    required this.nickname,
    this.profileImage,
    required this.createdAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] ?? 0,
      email: json['email'] ?? '',
      nickname: json['nickname'] ?? '',
      profileImage: json['profile_image'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : DateTime.now(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'email': email,
      'nickname': nickname,
      'profile_image': profileImage,
      'created_at': createdAt.toIso8601String(),
    };
  }
}