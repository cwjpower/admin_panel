import 'package:flutter/material.dart';
import 'book_upload_screens.dart';
import 'publishers_manage_screen.dart';
import 'books_manage_screen.dart'; // AllBooksManageScreen import

class AdminLayout extends StatefulWidget {
  const AdminLayout({Key? key}) : super(key: key);

  @override
  State<AdminLayout> createState() => _AdminLayoutState();
}

class _AdminLayoutState extends State<AdminLayout> {
  String _selectedMenu = 'dashboard';
  String _adminName = '관리자';
  String _permission = 'super_admin';
  String _publisherName = '';

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    // 로그인 화면에서 전달된 정보 받기
    final args = ModalRoute.of(context)?.settings.arguments as Map<String, String>?;
    if (args != null) {
      setState(() {
        _adminName = args['name'] ?? '관리자';
        _permission = args['permission'] ?? 'super_admin';
        _publisherName = args['publisher_name'] ?? '';
      });
    }
  }

  void _onMenuSelected(String menu) {
    setState(() {
      _selectedMenu = menu;
    });
  }

  Future<void> _logout() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('로그아웃'),
        content: const Text('로그아웃 하시겠습니까?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('취소'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
            ),
            child: const Text('로그아웃'),
          ),
        ],
      ),
    );

    if (confirm == true && mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  }

  Widget _getContentWidget() {
    switch (_selectedMenu) {
      case 'dashboard':
        return DashboardScreen(permission: _permission);
      case 'publishers':
        return const PublishersManageScreen();
      case 'books':
      // ✅ 권한에 따라 다른 화면!
        return _permission == 'super_admin'
            ? const AllBooksManageScreen() // 슈퍼 관리자 → 전체 시리즈
            : const SeriesManageScreen();   // 출판사 관리자 → 내 시리즈만
      case 'series':
        return const SeriesManageScreen();
      case 'users':
        return const UsersManageScreen();
      case 'orders':
        return const OrdersManageScreen();
      case 'community':
        return const CommunityManageScreen();
      case 'marketing':
        return const MarketingScreen();
      case 'sales':
        return const SalesScreen();
      case 'banners':
        return const BannersManageScreen();
      case 'news':
        return const NewsManageScreen();
      case 'cash':
        return const CashManageScreen();
      case 'stats':
        return const StatsScreen();
      case 'settings':
        return const SettingsScreen();
      default:
        return DashboardScreen(permission: _permission);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Row(
        children: [
          // 왼쪽 사이드바
          Container(
            width: 260,
            color: Colors.grey.shade900,
            child: Column(
              children: [
                // 로고
                Container(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    children: [
                      Icon(
                        Icons.admin_panel_settings,
                        size: 48,
                        color: Colors.red.shade400,
                      ),
                      const SizedBox(height: 8),
                      const Text(
                        'HERO COMICS',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      Text(
                        _permission == 'super_admin' ? 'Super Admin' : _publisherName,
                        style: TextStyle(
                          color: Colors.grey.shade400,
                          fontSize: 12,
                        ),
                      ),
                    ],
                  ),
                ),
                const Divider(color: Colors.grey),

                // 메뉴 리스트
                Expanded(
                  child: ListView(
                    padding: const EdgeInsets.symmetric(vertical: 8),
                    children: _permission == 'super_admin'
                        ? _buildSuperAdminMenus()
                        : _buildPublisherAdminMenus(),
                  ),
                ),

                // 하단 어드민 정보
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    border: Border(
                      top: BorderSide(color: Colors.grey.shade700),
                    ),
                  ),
                  child: Row(
                    children: [
                      CircleAvatar(
                        backgroundColor: _permission == 'super_admin'
                            ? Colors.red.shade400
                            : Colors.blue.shade400,
                        child: Text(
                          _adminName.isNotEmpty ? _adminName[0] : 'A',
                          style: const TextStyle(color: Colors.white),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              _adminName,
                              style: const TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            Text(
                              _permission == 'super_admin' ? '슈퍼 관리자' : '출판사 관리자',
                              style: TextStyle(
                                color: Colors.grey.shade400,
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ),
                      ),
                      IconButton(
                        icon: const Icon(Icons.logout, color: Colors.white),
                        onPressed: _logout,
                        tooltip: '로그아웃',
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // 메인 컨텐츠 영역
          Expanded(
            child: Column(
              children: [
                // 상단 AppBar
                Container(
                  height: 60,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.grey.shade300,
                        blurRadius: 4,
                        offset: const Offset(0, 2),
                      ),
                    ],
                  ),
                  child: Row(
                    children: [
                      const SizedBox(width: 20),
                      Text(
                        _getMenuTitle(_selectedMenu),
                        style: const TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const Spacer(),
                      IconButton(
                        icon: const Icon(Icons.notifications_outlined),
                        onPressed: () {},
                        tooltip: '알림',
                      ),
                      const SizedBox(width: 20),
                    ],
                  ),
                ),

                // 메인 컨텐츠
                Expanded(
                  child: Container(
                    color: Colors.grey.shade100,
                    child: _getContentWidget(),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ========== 슈퍼 어드민 메뉴 ==========
  List<Widget> _buildSuperAdminMenus() {
    return [
      _buildMenuItem(
        icon: Icons.dashboard,
        title: '대시보드',
        menu: 'dashboard',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('플랫폼 관리'),
      _buildMenuItem(
        icon: Icons.business,
        title: '출판사 관리',
        menu: 'publishers',
        badge: 'SUPER',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('콘텐츠 관리'),
      _buildMenuItem(
        icon: Icons.menu_book,
        title: '전체 책 관리',
        menu: 'books',
      ),
      _buildMenuItem(
        icon: Icons.collections_bookmark,
        title: '시리즈 관리',
        menu: 'series',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('사용자 관리'),
      _buildMenuItem(
        icon: Icons.people,
        title: '회원 관리',
        menu: 'users',
      ),
      _buildMenuItem(
        icon: Icons.shopping_cart,
        title: '주문 관리',
        menu: 'orders',
      ),
      _buildMenuItem(
        icon: Icons.forum,
        title: '커뮤니티 관리',
        menu: 'community',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('사이트 설정'),
      _buildMenuItem(
        icon: Icons.image,
        title: '배너 관리',
        menu: 'banners',
      ),
      _buildMenuItem(
        icon: Icons.article,
        title: '뉴스/공지',
        menu: 'news',
      ),
      _buildMenuItem(
        icon: Icons.card_giftcard,
        title: '캐시/쿠폰',
        menu: 'cash',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('기타'),
      _buildMenuItem(
        icon: Icons.bar_chart,
        title: '전체 통계',
        menu: 'stats',
      ),
      _buildMenuItem(
        icon: Icons.settings,
        title: '시스템 설정',
        menu: 'settings',
      ),
    ];
  }

  // ========== 출판사 어드민 메뉴 ==========
  List<Widget> _buildPublisherAdminMenus() {
    return [
      _buildMenuItem(
        icon: Icons.dashboard,
        title: '대시보드',
        menu: 'dashboard',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('내 콘텐츠'),
      _buildMenuItem(
        icon: Icons.collections_bookmark,
        title: '내 시리즈 관리',
        menu: 'series',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('마케팅'),
      _buildMenuItem(
        icon: Icons.campaign,
        title: '이벤트/프로모션',
        menu: 'marketing',
      ),
      _buildMenuItem(
        icon: Icons.image,
        title: '배너 신청',
        menu: 'banners',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('매출 관리'),
      _buildMenuItem(
        icon: Icons.attach_money,
        title: '매출 현황',
        menu: 'sales',
      ),
      _buildMenuItem(
        icon: Icons.bar_chart,
        title: '통계',
        menu: 'stats',
      ),
      const SizedBox(height: 8),
      _buildMenuHeader('기타'),
      _buildMenuItem(
        icon: Icons.forum,
        title: '리뷰 관리',
        menu: 'community',
      ),
      _buildMenuItem(
        icon: Icons.settings,
        title: '설정',
        menu: 'settings',
      ),
    ];
  }

  Widget _buildMenuHeader(String title) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 8, 16, 4),
      child: Text(
        title,
        style: TextStyle(
          color: Colors.grey.shade500,
          fontSize: 11,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    required String menu,
    String? badge,
  }) {
    final isSelected = _selectedMenu == menu;
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        color: isSelected ? Colors.red.shade700 : Colors.transparent,
        borderRadius: BorderRadius.circular(8),
      ),
      child: ListTile(
        leading: Icon(
          icon,
          color: isSelected ? Colors.white : Colors.grey.shade400,
          size: 22,
        ),
        title: Row(
          children: [
            Expanded(
              child: Text(
                title,
                style: TextStyle(
                  color: isSelected ? Colors.white : Colors.grey.shade300,
                  fontSize: 14,
                  fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                ),
              ),
            ),
            if (badge != null) ...[
              const SizedBox(width: 4),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                decoration: BoxDecoration(
                  color: badge == 'SUPER' ? Colors.purple.shade400 : Colors.green.shade400,
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  badge,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 9,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ],
        ),
        onTap: () => _onMenuSelected(menu),
        dense: true,
        visualDensity: VisualDensity.compact,
      ),
    );
  }

  String _getMenuTitle(String menu) {
    switch (menu) {
      case 'dashboard': return '대시보드';
      case 'publishers': return '출판사 관리';
      case 'books': return _permission == 'super_admin' ? '전체 책 관리' : '내 책 관리';
      case 'series': return _permission == 'super_admin' ? '시리즈 관리' : '내 시리즈 관리';
      case 'users': return '회원 관리';
      case 'orders': return '주문 관리';
      case 'community': return _permission == 'super_admin' ? '커뮤니티 관리' : '리뷰 관리';
      case 'marketing': return '이벤트/프로모션';
      case 'sales': return '매출 현황';
      case 'banners': return _permission == 'super_admin' ? '배너 관리' : '배너 신청';
      case 'news': return '뉴스/공지 관리';
      case 'cash': return '캐시/쿠폰 관리';
      case 'stats': return '통계';
      case 'settings': return '설정';
      default: return '대시보드';
    }
  }
}

// ========== 각 화면들 (간단한 플레이스홀더) ==========

class DashboardScreen extends StatelessWidget {
  final String permission;
  const DashboardScreen({Key? key, required this.permission}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.dashboard,
            size: 64,
            color: permission == 'super_admin' ? Colors.red.shade400 : Colors.blue.shade400,
          ),
          const SizedBox(height: 16),
          Text(
            permission == 'super_admin' ? '슈퍼 어드민 대시보드' : '출판사 대시보드',
            style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          Text(
            permission == 'super_admin'
                ? '전체 플랫폼 통계가 표시됩니다'
                : '내 출판사 통계가 표시됩니다',
            style: TextStyle(color: Colors.grey.shade600),
          ),
        ],
      ),
    );
  }
}

// ❌ 가짜 BooksManageScreen 삭제됨!
// AllBooksManageScreen을 사용하므로 필요 없음

class UsersManageScreen extends StatelessWidget {
  const UsersManageScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('회원 관리 화면'));
  }
}

class OrdersManageScreen extends StatelessWidget {
  const OrdersManageScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('주문 관리 화면'));
  }
}

class CommunityManageScreen extends StatelessWidget {
  const CommunityManageScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('커뮤니티/리뷰 관리 화면'));
  }
}

class MarketingScreen extends StatelessWidget {
  const MarketingScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.campaign, size: 64, color: Colors.orange.shade400),
          const SizedBox(height: 16),
          const Text('이벤트/프로모션', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          const Text('할인, 쿠폰 발행, 이벤트 생성'),
        ],
      ),
    );
  }
}

class SalesScreen extends StatelessWidget {
  const SalesScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.attach_money, size: 64, color: Colors.green.shade600),
          const SizedBox(height: 16),
          const Text('매출 현황', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          const Text('판매 내역, 정산 내역, 수익 통계'),
        ],
      ),
    );
  }
}

class BannersManageScreen extends StatelessWidget {
  const BannersManageScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('배너 관리 화면'));
  }
}

class NewsManageScreen extends StatelessWidget {
  const NewsManageScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('뉴스/공지 관리 화면'));
  }
}

class CashManageScreen extends StatelessWidget {
  const CashManageScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('캐시/쿠폰 관리 화면'));
  }
}

class StatsScreen extends StatelessWidget {
  const StatsScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('통계 화면'));
  }
}

class SettingsScreen extends StatelessWidget {
  const SettingsScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const Center(child: Text('설정 화면'));
  }
}