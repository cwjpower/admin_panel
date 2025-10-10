import 'package:flutter/material.dart';
import '../utils/colors.dart';
import '../services/storage_service.dart';
import 'login_screen.dart';

class MoreScreen extends StatefulWidget {
  @override
  _MoreScreenState createState() => _MoreScreenState();
}

class _MoreScreenState extends State<MoreScreen> {
  String userName = '';
  String userEmail = '';
  String userLevel = '1';

  @override
  void initState() {
    super.initState();
    _loadUserInfo();
  }

  Future<void> _loadUserInfo() async {
    final name = await StorageService.getUserName();
    final email = await StorageService.getUserEmail();
    final level = await StorageService.getUserLevel();

    setState(() {
      userName = name ?? '테스터';
      userEmail = email ?? 'test@test.com';
      userLevel = level ?? '1';
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(
          '더보기',
          style: TextStyle(
            color: AppColors.textDark,
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // 사용자 정보
            Container(
              color: Colors.white,
              padding: EdgeInsets.all(16),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundColor: AppColors.primaryRed,
                    child: Text(
                      userName.isNotEmpty ? userName[0].toUpperCase() : 'U',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          userName,
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: AppColors.textDark,
                          ),
                        ),
                        Text(
                          userEmail,
                          style: TextStyle(
                            fontSize: 14,
                            color: AppColors.textGrey,
                          ),
                        ),
                      ],
                    ),
                  ),
                  Icon(
                    Icons.chevron_right,
                    color: AppColors.textGrey,
                  ),
                ],
              ),
            ),

            SizedBox(height: 8),

            // 내 캐시/포인트
            Container(
              color: Colors.white,
              padding: EdgeInsets.all(16),
              child: Row(
                children: [
                  Expanded(
                    child: _buildBalanceItem(
                      icon: Icons.account_balance_wallet,
                      label: '캐시',
                      value: '0',
                      color: Colors.blue,
                    ),
                  ),
                  Container(
                    height: 40,
                    width: 1,
                    color: Colors.grey[300],
                  ),
                  Expanded(
                    child: _buildBalanceItem(
                      icon: Icons.stars,
                      label: '포인트',
                      value: '1,000',
                      color: Colors.orange,
                    ),
                  ),
                  Container(
                    height: 40,
                    width: 1,
                    color: Colors.grey[300],
                  ),
                  Expanded(
                    child: _buildBalanceItem(
                      icon: Icons.card_giftcard,
                      label: '쿠폰',
                      value: '0',
                      color: Colors.green,
                    ),
                  ),
                ],
              ),
            ),

            SizedBox(height: 8),

            // 메뉴 섹션 1 - 내 서재
            _buildMenuSection(
              title: '내 서재',
              items: [
                _buildMenuItem(
                  icon: Icons.book,
                  title: '구매한 작품',
                  onTap: () {
                    // TODO: 구매 목록 화면
                  },
                ),
                _buildMenuItem(
                  icon: Icons.history,
                  title: '최근 본 작품',
                  onTap: () {
                    // TODO: 최근 본 작품
                  },
                ),
                _buildMenuItem(
                  icon: Icons.favorite,
                  title: '찜한 작품',
                  onTap: () {
                    // TODO: 찜 목록
                  },
                ),
                _buildMenuItem(
                  icon: Icons.download,
                  title: '다운로드 관리',
                  onTap: () {
                    // TODO: 다운로드 관리
                  },
                ),
              ],
            ),

            SizedBox(height: 8),

            // 메뉴 섹션 2 - 혜택
            _buildMenuSection(
              title: '혜택',
              items: [
                _buildMenuItem(
                  icon: Icons.local_offer,
                  title: '이벤트',
                  badge: 'NEW',
                  onTap: () {
                    // TODO: 이벤트 화면
                  },
                ),
                _buildMenuItem(
                  icon: Icons.card_membership,
                  title: '구독 관리',
                  onTap: () {
                    // TODO: 구독 관리
                  },
                ),
                _buildMenuItem(
                  icon: Icons.account_balance_wallet_outlined,
                  title: '충전소',
                  onTap: () {
                    // TODO: 캐시 충전
                  },
                ),
              ],
            ),

            SizedBox(height: 8),

            // 메뉴 섹션 3 - 서비스
            _buildMenuSection(
              title: '서비스',
              items: [
                _buildMenuItem(
                  icon: Icons.campaign,
                  title: '공지사항',
                  onTap: () {
                    // TODO: 공지사항
                  },
                ),
                _buildMenuItem(
                  icon: Icons.help_outline,
                  title: '고객센터',
                  onTap: () {
                    // TODO: 고객센터
                  },
                ),
                _buildMenuItem(
                  icon: Icons.settings,
                  title: '설정',
                  onTap: () {
                    // TODO: 설정 화면
                  },
                ),
                _buildMenuItem(
                  icon: Icons.description,
                  title: '이용약관',
                  onTap: () {
                    // TODO: 약관
                  },
                ),
              ],
            ),

            SizedBox(height: 8),

            // 로그아웃 버튼
            Container(
              color: Colors.white,
              child: ListTile(
                leading: Icon(Icons.logout, color: Colors.red),
                title: Text(
                  '로그아웃',
                  style: TextStyle(color: Colors.red),
                ),
                onTap: () async {
                  final result = await showDialog<bool>(
                    context: context,
                    builder: (context) => AlertDialog(
                      title: Text('로그아웃'),
                      content: Text('정말 로그아웃 하시겠습니까?'),
                      actions: [
                        TextButton(
                          onPressed: () => Navigator.pop(context, false),
                          child: Text('취소'),
                        ),
                        TextButton(
                          onPressed: () => Navigator.pop(context, true),
                          child: Text(
                            '로그아웃',
                            style: TextStyle(color: Colors.red),
                          ),
                        ),
                      ],
                    ),
                  );

                  if (result == true) {
                    await StorageService.clearAuthData();
                    Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(builder: (context) => LoginScreen()),
                          (route) => false,
                    );
                  }
                },
              ),
            ),

            SizedBox(height: 20),

            // 버전 정보
            Padding(
              padding: EdgeInsets.all(16),
              child: Text(
                '버전 1.0.0',
                style: TextStyle(
                  color: AppColors.textGrey,
                  fontSize: 12,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildBalanceItem({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return InkWell(
      onTap: () {
        // TODO: 각 항목별 상세 화면
      },
      child: Column(
        children: [
          Icon(icon, color: color, size: 24),
          SizedBox(height: 4),
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              color: AppColors.textGrey,
            ),
          ),
          SizedBox(height: 2),
          Text(
            value,
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: AppColors.textDark,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMenuSection({
    required String title,
    required List<Widget> items,
  }) {
    return Container(
      color: Colors.white,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: EdgeInsets.fromLTRB(16, 16, 16, 8),
            child: Text(
              title,
              style: TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                color: AppColors.textGrey,
              ),
            ),
          ),
          ...items,
        ],
      ),
    );
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    String? badge,
    required VoidCallback onTap,
  }) {
    return ListTile(
      leading: Icon(icon, size: 20, color: AppColors.textDark),
      title: Row(
        children: [
          Text(title),
          if (badge != null) ...[
            SizedBox(width: 8),
            Container(
              padding: EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(
                color: AppColors.primaryRed,
                borderRadius: BorderRadius.circular(4),
              ),
              child: Text(
                badge,
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ],
        ],
      ),
      trailing: Icon(Icons.chevron_right, size: 20, color: AppColors.textGrey),
      onTap: onTap,
    );
  }
}