import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/book.dart';
import '../utils/colors.dart';
import 'book_detail_screen.dart';

class CategoryScreen extends StatefulWidget {
  @override
  _CategoryScreenState createState() => _CategoryScreenState();
}

class _CategoryScreenState extends State<CategoryScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;

  // 카테고리별 책 목록
  Map<String, List<Book>> categoryBooks = {
    '전체': [],
    '소년만화': [],
    '청년만화': [],
    '순정만화': [],
  };

  // 장르별 필터
  final List<String> genres = [
    '전체', '액션', '판타지', '로맨스', '스포츠',
    '개그', '일상', '스릴러', '무협', 'SF'
  ];

  String selectedGenre = '전체';
  String selectedSort = '인기순';
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _loadCategoryData();
  }

  Future<void> _loadCategoryData() async {
    setState(() {
      isLoading = true;
    });

    try {
      // 각 카테고리별로 데이터 로드
      final allBooks = await ApiService.fetchBooks();

      setState(() {
        categoryBooks['전체'] = allBooks;
        // TODO: 카테고리별 필터링 (서버에서 처리하도록 수정 필요)
        categoryBooks['소년만화'] = allBooks.where((b) => b.id.hashCode % 3 == 0).toList();
        categoryBooks['청년만화'] = allBooks.where((b) => b.id.hashCode % 3 == 1).toList();
        categoryBooks['순정만화'] = allBooks.where((b) => b.id.hashCode % 3 == 2).toList();
        isLoading = false;
      });
    } catch (e) {
      print('Category load error: $e');
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(
          '카테고리',
          style: TextStyle(
            color: AppColors.textDark,
            fontWeight: FontWeight.bold,
          ),
        ),
        bottom: TabBar(
          controller: _tabController,
          labelColor: AppColors.primaryRed,
          unselectedLabelColor: AppColors.textGrey,
          indicatorColor: AppColors.primaryRed,
          tabs: [
            Tab(text: '전체'),
            Tab(text: '소년만화'),
            Tab(text: '청년만화'),
            Tab(text: '순정만화'),
          ],
        ),
      ),
      body: Column(
        children: [
          // 장르 필터
          Container(
            height: 50,
            padding: EdgeInsets.symmetric(vertical: 8),
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: EdgeInsets.symmetric(horizontal: 16),
              itemCount: genres.length,
              itemBuilder: (context, index) {
                final genre = genres[index];
                final isSelected = selectedGenre == genre;

                return Padding(
                  padding: EdgeInsets.only(right: 8),
                  child: ChoiceChip(
                    label: Text(genre),
                    selected: isSelected,
                    selectedColor: AppColors.primaryRed,
                    labelStyle: TextStyle(
                      color: isSelected ? Colors.white : AppColors.textDark,
                      fontSize: 12,
                    ),
                    onSelected: (selected) {
                      setState(() {
                        selectedGenre = genre;
                      });
                      // TODO: 장르별 필터링
                    },
                  ),
                );
              },
            ),
          ),

          // 정렬 옵션
          Container(
            padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  '${_getCurrentBooks().length}개의 작품',
                  style: TextStyle(
                    color: AppColors.textGrey,
                    fontSize: 12,
                  ),
                ),
                DropdownButton<String>(
                  value: selectedSort,
                  items: ['인기순', '최신순', '평점순', '가격순'].map((String value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Text(
                        value,
                        style: TextStyle(fontSize: 12),
                      ),
                    );
                  }).toList(),
                  onChanged: (value) {
                    setState(() {
                      selectedSort = value!;
                    });
                    // TODO: 정렬 로직
                  },
                  underline: SizedBox(),
                  icon: Icon(Icons.arrow_drop_down, size: 20),
                ),
              ],
            ),
          ),

          Divider(height: 1),

          // 책 그리드
          Expanded(
            child: isLoading
                ? Center(
              child: CircularProgressIndicator(
                color: AppColors.primaryRed,
              ),
            )
                : TabBarView(
              controller: _tabController,
              children: [
                _buildBookGrid(categoryBooks['전체']!),
                _buildBookGrid(categoryBooks['소년만화']!),
                _buildBookGrid(categoryBooks['청년만화']!),
                _buildBookGrid(categoryBooks['순정만화']!),
              ],
            ),
          ),
        ],
      ),
    );
  }

  List<Book> _getCurrentBooks() {
    final categories = ['전체', '소년만화', '청년만화', '순정만화'];
    final currentCategory = categories[_tabController.index];
    return categoryBooks[currentCategory] ?? [];
  }

  Widget _buildBookGrid(List<Book> books) {
    if (books.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.book,
              size: 80,
              color: Colors.grey[300],
            ),
            SizedBox(height: 16),
            Text(
              '작품이 없습니다',
              style: TextStyle(
                color: AppColors.textGrey,
              ),
            ),
          ],
        ),
      );
    }

    return GridView.builder(
      padding: EdgeInsets.all(16),
      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3,
        childAspectRatio: 0.65,
        crossAxisSpacing: 12,
        mainAxisSpacing: 16,
      ),
      itemCount: books.length,
      itemBuilder: (context, index) {
        return _buildBookItem(books[index]);
      },
    );
  }

  Widget _buildBookItem(Book book) {
    final bookIdInt = int.tryParse(book.id) ?? 0;
    final colors = [
      Colors.red[400]!,
      Colors.blue[400]!,
      Colors.green[400]!,
      Colors.orange[400]!,
      Colors.purple[400]!,
    ];

    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => BookDetailScreen(book: book),
          ),
        );
      },
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 책 표지
          Expanded(
            child: Container(
              decoration: BoxDecoration(
                color: colors[bookIdInt % 5],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.book, color: Colors.white, size: 30),
                    SizedBox(height: 4),
                    Text(
                      book.title.split(' ').first,
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
          SizedBox(height: 8),
          // 책 정보
          Text(
            book.title,
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.bold,
              color: AppColors.textDark,
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          Text(
            book.author,
            style: TextStyle(
              fontSize: 10,
              color: AppColors.textGrey,
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          Row(
            children: [
              Icon(Icons.star, size: 10, color: Colors.orange),
              SizedBox(width: 2),
              Text(
                '${book.rating}',
                style: TextStyle(fontSize: 10),
              ),
            ],
          ),
          Text(
            '₩${book.price}',
            style: TextStyle(
              fontSize: 11,
              color: AppColors.primaryRed,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }
}