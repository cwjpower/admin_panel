import 'package:flutter/material.dart';
import '../services/admin_api_service.dart';

class PublisherDetailScreen extends StatefulWidget {
  final Map<String, dynamic> publisher;

  const PublisherDetailScreen({Key? key, required this.publisher}) : super(key: key);

  @override
  State<PublisherDetailScreen> createState() => _PublisherDetailScreenState();
}

class _PublisherDetailScreenState extends State<PublisherDetailScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  Map<String, dynamic>? _detailData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _loadDetail();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _loadDetail() async {
    setState(() => _isLoading = true);

    try {
      final response = await AdminApiService.get(
          'publishers/detail.php?publisher_id=${widget.publisher['publisher_id']}'
      );

      if (response['code'] == 0) {
        setState(() {
          _detailData = response['data'];
          _isLoading = false;
        });
      } else {
        _showError(response['msg'] ?? '데이터를 불러올 수 없습니다.');
        setState(() => _isLoading = false);
      }
    } catch (e) {
      _showError('오류: $e');
      setState(() => _isLoading = false);
    }
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message), backgroundColor: Colors.red),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.publisher['publisher_name'] ?? '출판사 상세'),
        backgroundColor: Colors.red.shade700,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.edit),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('편집 기능 준비 중')),
              );
            },
            tooltip: '편집',
          ),
        ],
        bottom: TabBar(
          controller: _tabController,
          labelColor: Colors.white,
          unselectedLabelColor: Colors.white70,
          indicatorColor: Colors.white,
          tabs: const [
            Tab(text: '기본정보'),
            Tab(text: '시리즈'),
            Tab(text: '매출/정산'),
            Tab(text: '통계'),
          ],
        ),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : TabBarView(
        controller: _tabController,
        children: [
          _buildInfoTab(),
          _buildSeriesTab(),
          _buildSalesTab(),
          _buildStatsTab(),
        ],
      ),
    );
  }

  // 기본 정보 탭
  Widget _buildInfoTab() {
    final publisher = _detailData?['publisher'] ?? {};
    final stats = _detailData?['stats'] ?? {};

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 요약 카드
          Card(
            color: Colors.blue.shade50,
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Row(
                children: [
                  Expanded(
                    child: _buildSummaryItem(
                      '등록 시리즈',
                      '${stats['total_series'] ?? 0}개',
                      Icons.collections_bookmark,
                      Colors.blue,
                    ),
                  ),
                  Expanded(
                    child: _buildSummaryItem(
                      '총 권수',
                      '${stats['total_volumes'] ?? 0}권',
                      Icons.book,
                      Colors.green,
                    ),
                  ),
                  Expanded(
                    child: _buildSummaryItem(
                      '총 페이지',
                      '${stats['total_pages'] ?? 0}장',
                      Icons.description,
                      Colors.orange,
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),

          // 기본 정보
          const Text(
            '기본 정보',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildInfoRow('출판사명', publisher['publisher_name'] ?? '-'),
                  _buildInfoRow('출판사 코드', publisher['publisher_code'] ?? '-'),
                  _buildInfoRow('한글명', publisher['publisher_name_ko'] ?? '-'),
                  _buildInfoRow('상태', _getStatusText(publisher['status'])),
                  _buildInfoRow('등록일', publisher['created_at'] ?? '-'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),

          // 연락처 정보
          const Text(
            '연락처 정보',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildInfoRow('담당자', publisher['contact_name'] ?? '-'),
                  _buildInfoRow('이메일', publisher['contact_email'] ?? '-'),
                  _buildInfoRow('전화번호', publisher['contact_phone'] ?? '-'),
                  _buildInfoRow('웹사이트', publisher['website'] ?? '-'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),

          // 정산 정보
          const Text(
            '정산 정보',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  _buildInfoRow('수수료율', '${publisher['commission_rate'] ?? 30}%'),
                  _buildInfoRow('은행', publisher['bank_name'] ?? '-'),
                  _buildInfoRow('계좌번호', publisher['bank_account'] ?? '-'),
                  _buildInfoRow('예금주', publisher['account_holder'] ?? '-'),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  // 시리즈 탭
  Widget _buildSeriesTab() {
    final seriesList = _detailData?['series'] ?? [];

    if (seriesList.isEmpty) {
      return const Center(
        child: Text('등록된 시리즈가 없습니다.'),
      );
    }

    return GridView.builder(
      padding: const EdgeInsets.all(20),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 4,
        crossAxisSpacing: 20,
        mainAxisSpacing: 20,
        childAspectRatio: 0.7,
      ),
      itemCount: seriesList.length,
      itemBuilder: (context, index) {
        final series = seriesList[index];
        return _buildSeriesCard(series);
      },
    );
  }

  Widget _buildSeriesCard(Map<String, dynamic> series) {
    return Card(
      elevation: 2,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 표지
          Expanded(
            child: Container(
              width: double.infinity,
              decoration: BoxDecoration(
                color: Colors.grey.shade300,
                borderRadius: const BorderRadius.vertical(top: Radius.circular(4)),
              ),
              child: series['cover_image'] != null
                  ? Image.network(series['cover_image'], fit: BoxFit.cover)
                  : Icon(Icons.collections_bookmark, size: 64, color: Colors.grey.shade500),
            ),
          ),

          // 정보
          Padding(
            padding: const EdgeInsets.all(12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  series['series_name'] ?? '',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text(
                  series['author'] ?? '',
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.grey.shade600,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.blue.shade100,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        '${series['volume_count']}권',
                        style: TextStyle(
                          fontSize: 12,
                          color: Colors.blue.shade700,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // 매출/정산 탭
  Widget _buildSalesTab() {
    final sales = _detailData?['sales'] ?? {};

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 매출 요약
          Row(
            children: [
              Expanded(
                child: _buildSalesCard(
                  '총 매출',
                  '₩${_formatNumber(sales['total_sales'] ?? 0)}',
                  Icons.attach_money,
                  Colors.blue,
                ),
              ),
              const SizedBox(width: 20),
              Expanded(
                child: _buildSalesCard(
                  '출판사 수익',
                  '₩${_formatNumber(sales['publisher_revenue'] ?? 0)}',
                  Icons.account_balance_wallet,
                  Colors.green,
                ),
              ),
            ],
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: _buildSalesCard(
                  '플랫폼 수수료',
                  '₩${_formatNumber(sales['platform_commission'] ?? 0)}',
                  Icons.receipt,
                  Colors.orange,
                ),
              ),
              const SizedBox(width: 20),
              Expanded(
                child: _buildSalesCard(
                  '정산 대기',
                  '₩${_formatNumber(sales['pending_settlement'] ?? 0)}',
                  Icons.hourglass_empty,
                  Colors.red,
                ),
              ),
            ],
          ),
          const SizedBox(height: 30),

          // 안내 메시지
          Card(
            color: Colors.amber.shade50,
            child: const Padding(
              padding: EdgeInsets.all(20),
              child: Row(
                children: [
                  Icon(Icons.info_outline, color: Colors.amber),
                  SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      '매출 및 정산 기능은 주문 시스템 구현 후 활성화됩니다.',
                      style: TextStyle(fontSize: 14),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  // 통계 탭
  Widget _buildStatsTab() {
    return const Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.bar_chart, size: 64, color: Colors.grey),
          SizedBox(height: 16),
          Text(
            '통계 기능 준비 중',
            style: TextStyle(fontSize: 18, color: Colors.grey),
          ),
          SizedBox(height: 8),
          Text(
            '인기 시리즈, 판매량, 리뷰 등의\n통계 정보가 표시될 예정입니다.',
            textAlign: TextAlign.center,
            style: TextStyle(color: Colors.grey),
          ),
        ],
      ),
    );
  }

  // 헬퍼 위젯들
  Widget _buildSummaryItem(String label, String value, IconData icon, Color color) {
    return Column(
      children: [
        Icon(icon, size: 32, color: color),
        const SizedBox(height: 8),
        Text(
          value,
          style: const TextStyle(
            fontSize: 24,
            fontWeight: FontWeight.bold,
          ),
        ),
        Text(
          label,
          style: const TextStyle(
            fontSize: 12,
            color: Colors.grey,
          ),
        ),
      ],
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(
              label,
              style: TextStyle(
                color: Colors.grey.shade600,
                fontWeight: FontWeight.w500,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontWeight: FontWeight.w500),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSalesCard(String title, String value, IconData icon, Color color) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, color: color, size: 32),
                const Spacer(),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 10),
            Text(
              title,
              style: const TextStyle(
                fontSize: 14,
                color: Colors.grey,
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _getStatusText(String? status) {
    switch (status) {
      case 'active':
        return '활성';
      case 'suspended':
        return '정지';
      case 'pending':
        return '승인대기';
      default:
        return '-';
    }
  }

  String _formatNumber(dynamic number) {
    if (number == null) return '0';
    final num = number is String ? int.tryParse(number) ?? 0 : number;
    return num.toString().replaceAllMapped(
      RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
          (Match m) => '${m[1]},',
    );
  }
}