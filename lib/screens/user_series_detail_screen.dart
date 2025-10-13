import 'package:flutter/material.dart';
import '../services/admin_api_service.dart';
import 'book_upload_screens.dart'; // VolumeManageScreen

class SeriesDetailScreen extends StatefulWidget {
  final Map<String, dynamic> series;

  const SeriesDetailScreen({Key? key, required this.series}) : super(key: key);

  @override
  State<SeriesDetailScreen> createState() => _SeriesDetailScreenState();
}

class _SeriesDetailScreenState extends State<SeriesDetailScreen> {
  final _formKey = GlobalKey<FormState>();
  bool _isLoading = true;
  bool _isSaving = false;

  Map<String, dynamic> _seriesData = {};

  // 폼 컨트롤러
  late TextEditingController _seriesNameController;
  late TextEditingController _seriesNameEnController;
  late TextEditingController _authorController;
  late TextEditingController _descriptionController;

  String _selectedCategory = 'MARVEL';
  String _selectedStatus = 'ongoing';

  final List<String> _categories = ['MARVEL', 'DC', 'IMAGE', 'JAPANESE', 'KOREAN'];
  final List<String> _statuses = ['ongoing', 'completed'];

  @override
  void initState() {
    super.initState();
    _initControllers();
    _loadSeriesDetail();
  }

  void _initControllers() {
    _seriesNameController = TextEditingController(text: widget.series['series_name'] ?? '');
    _seriesNameEnController = TextEditingController(text: widget.series['series_name_en'] ?? '');
    _authorController = TextEditingController(text: widget.series['author'] ?? '');
    _descriptionController = TextEditingController(text: widget.series['description'] ?? '');
    _selectedCategory = widget.series['category'] ?? 'MARVEL';
    _selectedStatus = widget.series['status'] ?? 'ongoing';
  }

  @override
  void dispose() {
    _seriesNameController.dispose();
    _seriesNameEnController.dispose();
    _authorController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _loadSeriesDetail() async {
    setState(() => _isLoading = true);

    try {
      // ✅ 수정: String을 int로 변환
      final result = await AdminApiService.getSeriesDetail(
        int.parse(widget.series['series_id'].toString()),
      );

      setState(() {
        _seriesData = result['data'];
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('데이터 로드 실패: $e')),
        );
      }
    }
  }

  Future<void> _saveSeries() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSaving = true);

    try {
      await AdminApiService.updateSeries(
        seriesId: int.parse(widget.series['series_id'].toString()),
        seriesName: _seriesNameController.text,
        seriesNameEn: _seriesNameEnController.text,
        author: _authorController.text,
        category: _selectedCategory,
        description: _descriptionController.text,
        status: _selectedStatus,
      );

      setState(() => _isSaving = false);

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('저장되었습니다')),
        );
        Navigator.pop(context, true); // 수정됨을 알림
      }
    } catch (e) {
      setState(() => _isSaving = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('저장 실패: $e')),
        );
      }
    }
  }

  Future<void> _deleteSeries() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('시리즈 삭제'),
        content: const Text('이 시리즈를 삭제하시겠습니까?\n삭제하면 복구할 수 없습니다.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('취소'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: const Text('삭제'),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    try {
      await AdminApiService.deleteSeries(
        seriesId: int.parse(widget.series['series_id'].toString()),
      );

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('삭제되었습니다')),
        );
        Navigator.pop(context, true);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('삭제 실패: $e')),
        );
      }
    }
  }

  void _navigateToVolumeManage() {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => VolumeManageScreen(
          seriesId: int.parse(widget.series['series_id'].toString()),
          seriesName: widget.series['series_name'] ?? '',
        ),
      ),
    ).then((_) => _loadSeriesDetail());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: Text(_seriesNameController.text),
        backgroundColor: Colors.white,
        foregroundColor: Colors.black,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.book, color: Colors.blue),
            onPressed: _navigateToVolumeManage,
            tooltip: '권 관리',
          ),
          IconButton(
            icon: const Icon(Icons.delete, color: Colors.red),
            onPressed: _deleteSeries,
            tooltip: '시리즈 삭제',
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildInfoCard(),
              const SizedBox(height: 16),
              _buildStatsCard(),
              const SizedBox(height: 16),
              _buildFormCard(),
              const SizedBox(height: 24),
              _buildSaveButton(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildInfoCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  width: 100,
                  height: 140,
                  decoration: BoxDecoration(
                    color: Colors.grey[300],
                    borderRadius: BorderRadius.circular(8),
                    image: _seriesData['cover_image'] != null
                        ? DecorationImage(
                      image: NetworkImage(_seriesData['cover_image']),
                      fit: BoxFit.cover,
                    )
                        : null,
                  ),
                  child: _seriesData['cover_image'] == null
                      ? const Icon(Icons.book, size: 48, color: Colors.grey)
                      : null,
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        _seriesData['series_name'] ?? '',
                        style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        _seriesData['author'] ?? '',
                        style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        _seriesData['publisher_name'] ?? '',
                        style: const TextStyle(fontSize: 14, color: Colors.blue),
                      ),
                      const SizedBox(height: 8),
                      Wrap(
                        spacing: 8,
                        children: [
                          Chip(
                            label: Text(_seriesData['category'] ?? ''),
                            backgroundColor: Colors.orange[50],
                          ),
                          Chip(
                            label: Text(_seriesData['status'] == 'ongoing' ? '연재중' : '완결'),
                            backgroundColor: _seriesData['status'] == 'ongoing'
                                ? Colors.green[50]
                                : Colors.grey[200],
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatsCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _buildStatItem('권수', '${_seriesData['actual_volumes'] ?? 0}권', Icons.book),
            _buildStatItem('페이지', '${_seriesData['total_pages'] ?? 0}p', Icons.description),
            _buildStatItem('상태', _seriesData['status'] == 'ongoing' ? '연재중' : '완결', Icons.check_circle),
          ],
        ),
      ),
    );
  }

  Widget _buildStatItem(String label, String value, IconData icon) {
    return Column(
      children: [
        Icon(icon, color: Colors.blue, size: 32),
        const SizedBox(height: 8),
        Text(value, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        Text(label, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
      ],
    );
  }

  Widget _buildFormCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('시리즈 정보 수정', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            TextFormField(
              controller: _seriesNameController,
              decoration: const InputDecoration(
                labelText: '시리즈명 (한글)',
                border: OutlineInputBorder(),
              ),
              validator: (value) => value?.isEmpty ?? true ? '시리즈명을 입력하세요' : null,
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: _seriesNameEnController,
              decoration: const InputDecoration(
                labelText: '시리즈명 (영문)',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: _authorController,
              decoration: const InputDecoration(
                labelText: '작가',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedCategory,
              decoration: const InputDecoration(
                labelText: '카테고리',
                border: OutlineInputBorder(),
              ),
              items: _categories.map((cat) => DropdownMenuItem(value: cat, child: Text(cat))).toList(),
              onChanged: (value) => setState(() => _selectedCategory = value!),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedStatus,
              decoration: const InputDecoration(
                labelText: '상태',
                border: OutlineInputBorder(),
              ),
              items: const [
                DropdownMenuItem(value: 'ongoing', child: Text('연재중')),
                DropdownMenuItem(value: 'completed', child: Text('완결')),
              ],
              onChanged: (value) => setState(() => _selectedStatus = value!),
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: _descriptionController,
              decoration: const InputDecoration(
                labelText: '작품 소개',
                border: OutlineInputBorder(),
                alignLabelWithHint: true,
              ),
              maxLines: 5,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSaveButton() {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton(
        onPressed: _isSaving ? null : _saveSeries,
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.blue,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
        child: _isSaving
            ? const CircularProgressIndicator(color: Colors.white)
            : const Text('저장', style: TextStyle(fontSize: 16, color: Colors.white)),
      ),
    );
  }
}