import 'package:flutter/material.dart';
import '../models/book.dart';
import '../utils/colors.dart';
import '../services/api_service.dart';
import '../services/storage_service.dart';
import 'action_viewer_screen.dart';

class BookDetailScreen extends StatefulWidget {
  final Book book;

  const BookDetailScreen({Key? key, required this.book}) : super(key: key);

  @override
  _BookDetailScreenState createState() => _BookDetailScreenState();
}

class _BookDetailScreenState extends State<BookDetailScreen> {
  bool isFavorite = false;
  bool isInCart = false;
  int selectedVolume = 1;
  List<int> ownedVolumes = []; // 구매한 권수
  List<Book> recommendedBooks = [];

  // 탭 관련
  final List<String> tabs = ['작품정보', '회차정보', '리뷰'];
  int selectedTabIndex = 0;

  @override
  void initState() {
    super.initState();
    _loadBookDetails();
  }

  Future<void> _loadBookDetails() async {
    // TODO: 실제 API 호출
    // 추천 작품 로드
    try {
      recommendedBooks = await ApiService.fetchBooks(category: 'recommended');
      setState(() {});
    } catch (e) {
      print('Error loading recommendations: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: CustomScrollView(
        slivers: [
          // 커스텀 앱바
          SliverAppBar(
            expandedHeight: 300,
            pinned: true,
            backgroundColor: Colors.black,
            flexibleSpace: FlexibleSpaceBar(
              background: Stack(
                fit: StackFit.expand,
                children: [
                  // 배경 이미지 (블러 처리)
                  Container(
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                        colors: [
                          Colors.black.withOpacity(0.3),
                          Colors.black.withOpacity(0.8),
                        ],
                      ),
                    ),
                  ),
                  // 책 표지
                  Center(
                    child: Container(
                      width: 150,
                      height: 200,
                      decoration: BoxDecoration(
                        color: _getBookColor(),
                        borderRadius: BorderRadius.circular(8),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.5),
                            blurRadius: 20,
                            offset: Offset(0, 10),
                          ),
                        ],
                      ),
                      child: Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.book, color: Colors.white, size: 50),
                            SizedBox(height: 8),
                            Text(
                              widget.book.title,
                              style: TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                              textAlign: TextAlign.center,
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            leading: IconButton(
              icon: Icon(Icons.arrow_back, color: Colors.white),
              onPressed: () => Navigator.pop(context),
            ),
            actions: [
              IconButton(
                icon: Icon(
                  isFavorite ? Icons.favorite : Icons.favorite_border,
                  color: isFavorite ? Colors.red : Colors.white,
                ),
                onPressed: () {
                  setState(() {
                    isFavorite = !isFavorite;
                  });
                },
              ),
              IconButton(
                icon: Icon(Icons.share, color: Colors.white),
                onPressed: () {
                  // TODO: 공유 기능
                },
              ),
            ],
          ),

          // 책 기본 정보
          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // 제목과 작가
                  Text(
                    widget.book.title,
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textDark,
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    widget.book.author,
                    style: TextStyle(
                      fontSize: 16,
                      color: AppColors.textGrey,
                    ),
                  ),
                  SizedBox(height: 8),

                  // 평점과 정보
                  Row(
                    children: [
                      // 평점
                      Row(
                        children: [
                          ...List.generate(5, (index) {
                            return Icon(
                              index < widget.book.rating.floor()
                                  ? Icons.star
                                  : Icons.star_border,
                              color: Colors.orange,
                              size: 16,
                            );
                          }),
                          SizedBox(width: 4),
                          Text(
                            '${widget.book.rating}',
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          Text(
                            ' (${widget.book.reviewCount})',
                            style: TextStyle(
                              color: AppColors.textGrey,
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ),
                      SizedBox(width: 16),
                      // 연재 상태
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: Colors.green,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          '연재중',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ],
                  ),

                  SizedBox(height: 16),

                  // 가격 정보
                  Container(
                    padding: EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.grey[100],
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Column(
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text('회당 구매'),
                            Text(
                              '₩${widget.book.price}',
                              style: TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                        Divider(),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text('전권 구매 (30% 할인)'),
                            Text(
                              '₩${(int.parse(widget.book.price) * 10 * 0.7).toInt()}',
                              style: TextStyle(
                                fontWeight: FontWeight.bold,
                                color: AppColors.primaryRed,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),

                  SizedBox(height: 16),

                  // 액션 버튼들
                  Row(
                    children: [
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: () {
                            // Action Viewer로 이동 (첫화 무료)
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => ActionViewerScreen(
                                  bookId: widget.book.id,
                                  episode: 1,  // 첫 화
                                  bookTitle: widget.book.title,
                                  mode: ViewMode.vertical,
                                ),
                              ),
                            );
                          },
                          icon: Icon(Icons.visibility),
                          label: Text('첫화 무료'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.grey[800],
                            padding: EdgeInsets.symmetric(vertical: 12),
                          ),
                        ),
                      ),
                      SizedBox(width: 8),
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: () {
                            setState(() {
                              isInCart = !isInCart;
                            });
                          },
                          icon: Icon(
                            isInCart ? Icons.shopping_cart : Icons.add_shopping_cart,
                          ),
                          label: Text(isInCart ? '담김' : '담기'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: isInCart ? Colors.grey : AppColors.primaryRed,
                            padding: EdgeInsets.symmetric(vertical: 12),
                          ),
                        ),
                      ),
                    ],
                  ),

                  SizedBox(height: 24),

                  // 탭 메뉴
                  Container(
                    decoration: BoxDecoration(
                      border: Border(
                        bottom: BorderSide(color: Colors.grey[300]!),
                      ),
                    ),
                    child: Row(
                      children: List.generate(tabs.length, (index) {
                        final isSelected = selectedTabIndex == index;
                        return Expanded(
                          child: InkWell(
                            onTap: () {
                              setState(() {
                                selectedTabIndex = index;
                              });
                            },
                            child: Container(
                              padding: EdgeInsets.symmetric(vertical: 16),
                              decoration: BoxDecoration(
                                border: Border(
                                  bottom: BorderSide(
                                    color: isSelected
                                        ? AppColors.primaryRed
                                        : Colors.transparent,
                                    width: 2,
                                  ),
                                ),
                              ),
                              child: Text(
                                tabs[index],
                                textAlign: TextAlign.center,
                                style: TextStyle(
                                  color: isSelected
                                      ? AppColors.primaryRed
                                      : AppColors.textGrey,
                                  fontWeight: isSelected
                                      ? FontWeight.bold
                                      : FontWeight.normal,
                                ),
                              ),
                            ),
                          ),
                        );
                      }),
                    ),
                  ),

                  SizedBox(height: 16),

                  // 탭 콘텐츠
                  if (selectedTabIndex == 0) _buildInfoTab(),
                  if (selectedTabIndex == 1) _buildEpisodesTab(),
                  if (selectedTabIndex == 2) _buildReviewsTab(),

                  SizedBox(height: 24),

                  // 추천 작품
                  Text(
                    '이 작품과 비슷한 만화',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textDark,
                    ),
                  ),
                  SizedBox(height: 12),
                  Container(
                    height: 180,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: 5,
                      itemBuilder: (context, index) {
                        return _buildRecommendedBook(index);
                      },
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

  Widget _buildInfoTab() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          '작품 소개',
          style: TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.bold,
          ),
        ),
        SizedBox(height: 8),
        Text(
          widget.book.description == null || widget.book.description!.isEmpty
              ? '최강의 닌자를 꿈꾸는 소년 나루토의 성장 스토리! 닌자 아카데미 졸업 후 사스케, 사쿠라와 함께 팀을 이뤄 다양한 임무를 수행하며 성장해나가는 이야기.'
              : widget.book.description!,
          style: TextStyle(
            color: AppColors.textGrey,
            height: 1.5,
          ),
        ),
        SizedBox(height: 16),
        _buildInfoRow('장르', '소년만화, 액션, 판타지'),
        _buildInfoRow('연재처', 'Weekly Jump'),
        _buildInfoRow('연재 시작', '2019.01.01'),
        _buildInfoRow('총 화수', '350화'),
      ],
    );
  }

  Widget _buildEpisodesTab() {
    return Column(
      children: List.generate(10, (index) {
        final episodeNum = 10 - index;
        final isOwned = ownedVolumes.contains(episodeNum);

        return ListTile(
          contentPadding: EdgeInsets.zero,
          leading: Container(
            width: 60,
            height: 80,
            decoration: BoxDecoration(
              color: _getBookColor().withOpacity(0.8),
              borderRadius: BorderRadius.circular(4),
            ),
            child: Center(
              child: Text(
                '$episodeNum화',
                style: TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
          title: Text('제 $episodeNum화'),
          subtitle: Text('2024.${12 - index}.01'),
          trailing: isOwned
              ? ElevatedButton(
            onPressed: () {
              // Action Viewer로 읽기
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ActionViewerScreen(
                    bookId: widget.book.id,
                    episode: episodeNum,
                    bookTitle: widget.book.title,
                    mode: ViewMode.vertical,
                  ),
                ),
              );
            },
            child: Text('읽기'),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.green,
            ),
          )
              : Text(
            '₩${widget.book.price}',
            style: TextStyle(
              color: AppColors.primaryRed,
              fontWeight: FontWeight.bold,
            ),
          ),
        );
      }),
    );
  }

  Widget _buildReviewsTab() {
    return Column(
      children: List.generate(5, (index) {
        return Container(
          margin: EdgeInsets.only(bottom: 16),
          padding: EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: Colors.grey[50],
            borderRadius: BorderRadius.circular(8),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  CircleAvatar(
                    radius: 16,
                    backgroundColor: Colors.grey[400],
                    child: Icon(Icons.person, size: 16, color: Colors.white),
                  ),
                  SizedBox(width: 8),
                  Text(
                    '독자${index + 1}',
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  Spacer(),
                  ...List.generate(5, (i) {
                    return Icon(
                      i < 4 ? Icons.star : Icons.star_border,
                      size: 12,
                      color: Colors.orange,
                    );
                  }),
                ],
              ),
              SizedBox(height: 8),
              Text(
                '정말 재미있게 읽고 있습니다! 다음 화가 기다려져요.',
                style: TextStyle(fontSize: 13),
              ),
            ],
          ),
        );
      }),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 80,
            child: Text(
              label,
              style: TextStyle(
                color: AppColors.textGrey,
              ),
            ),
          ),
          Expanded(
            child: Text(value),
          ),
        ],
      ),
    );
  }

  Widget _buildRecommendedBook(int index) {
    final colors = [
      Colors.blue[400]!,
      Colors.red[400]!,
      Colors.green[400]!,
      Colors.orange[400]!,
      Colors.purple[400]!,
    ];

    return Container(
      width: 100,
      margin: EdgeInsets.only(right: 12),
      child: Column(
        children: [
          Container(
            height: 130,
            decoration: BoxDecoration(
              color: colors[index % 5],
              borderRadius: BorderRadius.circular(8),
            ),
            child: Center(
              child: Icon(Icons.book, color: Colors.white, size: 30),
            ),
          ),
          SizedBox(height: 4),
          Text(
            '추천작품 ${index + 1}',
            style: TextStyle(fontSize: 12),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }

  Color _getBookColor() {
    final bookIdInt = int.tryParse(widget.book.id) ?? 0;
    final colors = [
      Colors.red[400]!,
      Colors.blue[400]!,
      Colors.green[400]!,
      Colors.orange[400]!,
      Colors.purple[400]!,
    ];
    return colors[bookIdInt % 5];
  }
}