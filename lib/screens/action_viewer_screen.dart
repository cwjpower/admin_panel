import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../utils/colors.dart';
import '../services/storage_service.dart';

class ActionViewerScreen extends StatefulWidget {
  final String bookId;
  final int episode;
  final String bookTitle;
  final ViewMode mode;

  const ActionViewerScreen({
    Key? key,
    required this.bookId,
    required this.episode,
    required this.bookTitle,
    this.mode = ViewMode.vertical,
  }) : super(key: key);

  @override
  _ActionViewerScreenState createState() => _ActionViewerScreenState();
}

enum ViewMode {
  vertical,    // 세로 스크롤 (웹툰)
  horizontal,  // 가로 프레임 (Action Viewer)
}

class _ActionViewerScreenState extends State<ActionViewerScreen> {
  List<dynamic> pages = [];
  bool isLoading = true;
  int currentPage = 0;
  PageController? pageController;
  ScrollController? scrollController;
  bool isMenuVisible = false;
  ViewMode currentMode = ViewMode.vertical;

  // 가로 모드용 변수들
  int currentFrame = 0;
  TransformationController transformationController = TransformationController();

  @override
  void initState() {
    super.initState();
    currentMode = widget.mode;

    // 전체 화면 모드
    SystemChrome.setEnabledSystemUIMode(SystemUiMode.immersiveSticky);

    if (currentMode == ViewMode.vertical) {
      scrollController = ScrollController();
      scrollController!.addListener(_onScroll);
    } else {
      pageController = PageController();
    }

    _loadPages();
    _loadProgress();
  }

  @override
  void dispose() {
    // 시스템 UI 복원
    SystemChrome.setEnabledSystemUIMode(SystemUiMode.edgeToEdge);

    pageController?.dispose();
    scrollController?.dispose();
    transformationController.dispose();

    // 진도 저장
    _saveProgress();

    super.dispose();
  }

  void _onScroll() {
    // 세로 스크롤 시 현재 페이지 계산
    if (scrollController != null && pages.isNotEmpty) {
      double offset = scrollController!.offset;
      double pageHeight = MediaQuery.of(context).size.height;
      int newPage = (offset / pageHeight).floor();

      if (newPage != currentPage && newPage >= 0 && newPage < pages.length) {
        setState(() {
          currentPage = newPage;
        });
      }
    }
  }

  Future<void> _loadPages() async {
    try {
      final response = await http.get(
        Uri.parse('http://34.64.84.117:8081/admin/apis/viewer/pages.php?book_id=${widget.bookId}&episode=${widget.episode}&mode=${currentMode.name}'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          pages = data['pages'] ?? [];
          isLoading = false;
        });
      }
    } catch (e) {
      print('Error loading pages: $e');
      setState(() {
        isLoading = false;
      });

      // 에러 시 더미 데이터
      setState(() {
        pages = List.generate(10, (index) => {
          'page_num': index + 1,
          'image_url': 'dummy_${index + 1}.jpg',
        });
        isLoading = false;
      });
    }
  }

  Future<void> _loadProgress() async {
    // 저장된 진도 불러오기
    final savedPage = await StorageService.getReadingProgress(widget.bookId, widget.episode);
    if (savedPage != null && savedPage > 0 && savedPage < pages.length) {
      setState(() {
        currentPage = savedPage;
      });

      // 저장된 페이지로 이동
      if (currentMode == ViewMode.vertical && scrollController != null) {
        Future.delayed(Duration(milliseconds: 500), () {
          scrollController!.jumpTo(savedPage * MediaQuery.of(context).size.height);
        });
      } else if (pageController != null) {
        Future.delayed(Duration(milliseconds: 500), () {
          pageController!.jumpToPage(savedPage);
        });
      }
    }
  }

  Future<void> _saveProgress() async {
    await StorageService.saveReadingProgress(
      widget.bookId,
      widget.episode,
      currentPage,
    );
  }

  void _toggleMenu() {
    setState(() {
      isMenuVisible = !isMenuVisible;
    });
  }

  void _switchViewMode() {
    setState(() {
      currentMode = currentMode == ViewMode.vertical
          ? ViewMode.horizontal
          : ViewMode.vertical;

      // 컨트롤러 재초기화
      if (currentMode == ViewMode.vertical) {
        pageController?.dispose();
        pageController = null;
        scrollController = ScrollController();
        scrollController!.addListener(_onScroll);
      } else {
        scrollController?.dispose();
        scrollController = null;
        pageController = PageController(initialPage: currentPage);
      }
    });
  }

  void _previousPage() {
    if (currentPage > 0) {
      setState(() {
        currentPage--;
      });

      if (currentMode == ViewMode.horizontal && pageController != null) {
        pageController!.previousPage(
          duration: Duration(milliseconds: 300),
          curve: Curves.easeInOut,
        );
      }
    }
  }

  void _nextPage() {
    if (currentPage < pages.length - 1) {
      setState(() {
        currentPage++;
      });

      if (currentMode == ViewMode.horizontal && pageController != null) {
        pageController!.nextPage(
          duration: Duration(milliseconds: 300),
          curve: Curves.easeInOut,
        );
      }
    }
  }

  Widget _buildVerticalViewer() {
    return ListView.builder(
      controller: scrollController,
      itemCount: pages.length,
      itemBuilder: (context, index) {
        return GestureDetector(
          onTap: _toggleMenu,
          child: Container(
            height: MediaQuery.of(context).size.height,
            color: Colors.black,
            child: InteractiveViewer(
              minScale: 1.0,
              maxScale: 4.0,
              child: Center(
                child: _buildPageContent(pages[index]),
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildHorizontalViewer() {
    return PageView.builder(
      controller: pageController,
      onPageChanged: (index) {
        setState(() {
          currentPage = index;
        });
      },
      itemCount: pages.length,
      itemBuilder: (context, index) {
        return GestureDetector(
          onTap: _toggleMenu,
          onHorizontalDragEnd: (details) {
            // 스와이프로 페이지 넘기기
            if (details.velocity.pixelsPerSecond.dx > 0) {
              _previousPage();
            } else {
              _nextPage();
            }
          },
          child: InteractiveViewer(
            transformationController: transformationController,
            minScale: 1.0,
            maxScale: 5.0,
            child: Center(
              child: _buildPageContent(pages[index]),
            ),
          ),
        );
      },
    );
  }

  Widget _buildPageContent(Map<String, dynamic> page) {
    // 실제로는 이미지 URL로 이미지 로드
    // 지금은 플레이스홀더
    return Container(
      width: double.infinity,
      height: double.infinity,
      color: Colors.grey[900],
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.image,
            size: 100,
            color: Colors.grey[600],
          ),
          SizedBox(height: 20),
          Text(
            '${widget.bookTitle}',
            style: TextStyle(
              color: Colors.grey[400],
              fontSize: 16,
            ),
          ),
          Text(
            '${widget.episode}화 - ${page['page_num']}페이지',
            style: TextStyle(
              color: Colors.white,
              fontSize: 24,
              fontWeight: FontWeight.bold,
            ),
          ),
          if (page['image_url'] != null)
            Text(
              '${page['image_url']}',
              style: TextStyle(
                color: Colors.grey[500],
                fontSize: 12,
              ),
            ),
        ],
      ),
    );

    // 실제 이미지 로드 코드 (나중에 활성화)
    // return Image.network(
    //   page['image_url'],
    //   fit: BoxFit.contain,
    //   loadingBuilder: (context, child, loadingProgress) {
    //     if (loadingProgress == null) return child;
    //     return Center(
    //       child: CircularProgressIndicator(
    //         value: loadingProgress.expectedTotalBytes != null
    //             ? loadingProgress.cumulativeBytesLoaded /
    //                 loadingProgress.expectedTotalBytes!
    //             : null,
    //       ),
    //     );
    //   },
    //   errorBuilder: (context, error, stackTrace) {
    //     return Container(
    //       color: Colors.grey[900],
    //       child: Center(
    //         child: Text(
    //           'Page ${page['page_num']}',
    //           style: TextStyle(color: Colors.white),
    //         ),
    //       ),
    //     );
    //   },
    // );
  }

  Widget _buildMenu() {
    return AnimatedOpacity(
      opacity: isMenuVisible ? 1.0 : 0.0,
      duration: Duration(milliseconds: 200),
      child: Container(
        color: Colors.black54,
        child: Column(
          children: [
            // 상단 메뉴
            Container(
              color: Colors.black87,
              child: SafeArea(
                bottom: false,
                child: Row(
                  children: [
                    IconButton(
                      icon: Icon(Icons.arrow_back, color: Colors.white),
                      onPressed: () => Navigator.pop(context),
                    ),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            widget.bookTitle,
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 14,
                            ),
                          ),
                          Text(
                            '${widget.episode}화 - ${currentPage + 1}/${pages.length}',
                            style: TextStyle(
                              color: Colors.grey[400],
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ),
                    ),
                    IconButton(
                      icon: Icon(
                        currentMode == ViewMode.vertical
                            ? Icons.view_column
                            : Icons.view_agenda,
                        color: Colors.white,
                      ),
                      onPressed: _switchViewMode,
                    ),
                    IconButton(
                      icon: Icon(Icons.settings, color: Colors.white),
                      onPressed: () {
                        // 설정 메뉴
                      },
                    ),
                  ],
                ),
              ),
            ),

            Spacer(),

            // 하단 컨트롤
            Container(
              color: Colors.black87,
              child: SafeArea(
                top: false,
                child: Column(
                  children: [
                    // 페이지 슬라이더
                    Slider(
                      value: currentPage.toDouble(),
                      min: 0,
                      max: (pages.length - 1).toDouble(),
                      divisions: pages.length - 1,
                      activeColor: AppColors.primaryRed,
                      onChanged: (value) {
                        setState(() {
                          currentPage = value.toInt();
                        });

                        if (currentMode == ViewMode.horizontal && pageController != null) {
                          pageController!.jumpToPage(currentPage);
                        } else if (scrollController != null) {
                          scrollController!.jumpTo(
                            currentPage * MediaQuery.of(context).size.height,
                          );
                        }
                      },
                    ),

                    // 컨트롤 버튼들
                    if (currentMode == ViewMode.horizontal)
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          IconButton(
                            icon: Icon(Icons.skip_previous, color: Colors.white),
                            onPressed: currentPage > 0 ? _previousPage : null,
                          ),
                          Container(
                            padding: EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                            decoration: BoxDecoration(
                              color: Colors.white24,
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              '${currentPage + 1} / ${pages.length}',
                              style: TextStyle(color: Colors.white),
                            ),
                          ),
                          IconButton(
                            icon: Icon(Icons.skip_next, color: Colors.white),
                            onPressed: currentPage < pages.length - 1 ? _nextPage : null,
                          ),
                        ],
                      ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: isLoading
          ? Center(
        child: CircularProgressIndicator(
          color: AppColors.primaryRed,
        ),
      )
          : Stack(
        children: [
          // 메인 뷰어
          currentMode == ViewMode.vertical
              ? _buildVerticalViewer()
              : _buildHorizontalViewer(),

          // 메뉴 오버레이
          if (isMenuVisible)
            Positioned.fill(
              child: _buildMenu(),
            ),
        ],
      ),
    );
  }
}