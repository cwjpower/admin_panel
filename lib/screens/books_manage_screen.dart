// lib/screens/books_manage_screen.dart
import 'package:flutter/material.dart';
import '../services/admin_api_service.dart';

class AllBooksManageScreen extends StatefulWidget {
  const AllBooksManageScreen({Key? key}) : super(key: key);

  @override
  State<AllBooksManageScreen> createState() => _AllBooksManageScreenState();
}

class _AllBooksManageScreenState extends State<AllBooksManageScreen> {
  List<dynamic> _seriesList = [];
  bool _isLoading = true;
  String _searchQuery = '';
  String? _selectedCategory;
  String? _selectedStatus;

  @override
  void initState() {
    super.initState();
    _loadSeries();
  }

  Future<void> _loadSeries() async {
    setState(() => _isLoading = true);

    try {
      final result = await AdminApiService.getAllSeries(
        search: _searchQuery.isNotEmpty ? _searchQuery : null,
        category: _selectedCategory,
        status: _selectedStatus,
      );

      if (result['success']) {
        setState(() {
          _seriesList = result['result'] ?? [];
          _isLoading = false;
        });
      } else {
        setState(() {
          _isLoading = false;
        });
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(result['message'] ?? 'Failed to load')),
          );
        }
      }
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: $e')),
        );
      }
    }
  }

  void _showEditSeriesDialog(dynamic series) {
    showDialog(
      context: context,
      builder: (context) => _EditSeriesDialog(
        series: series,
        onSeriesUpdated: _loadSeries,
      ),
    );
  }

  Future<void> _deleteSeries(int seriesId, String seriesName) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('시리즈 삭제'),
        content: Text('$seriesName을(를) 삭제하시겠습니까?\n\n모든 권도 함께 삭제됩니다.'),
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

    if (confirm == true) {
      try {
        final response = await AdminApiService.deleteSeries(seriesId: seriesId);

        if (mounted) {
          if (response['success']) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('시리즈가 삭제되었습니다')),
            );
            _loadSeries();
          } else {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(response['message'] ?? 'Failed')),
            );
          }
        }
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Error: $e')),
          );
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('전체 시리즈 관리'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadSeries,
          ),
        ],
      ),
      body: Column(
        children: [
          // 검색 및 필터
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              children: [
                // 검색
                TextField(
                  decoration: const InputDecoration(
                    labelText: '시리즈 검색',
                    prefixIcon: Icon(Icons.search),
                    border: OutlineInputBorder(),
                  ),
                  onChanged: (value) {
                    setState(() => _searchQuery = value);
                    _loadSeries();
                  },
                ),
                const SizedBox(height: 12),
                // 필터
                Row(
                  children: [
                    Expanded(
                      child: DropdownButtonFormField<String>(
                        decoration: const InputDecoration(
                          labelText: '카테고리',
                          border: OutlineInputBorder(),
                        ),
                        value: _selectedCategory,
                        items: const [
                          DropdownMenuItem(value: null, child: Text('전체')),
                          DropdownMenuItem(value: 'MARVEL', child: Text('MARVEL')),
                          DropdownMenuItem(value: 'DC', child: Text('DC')),
                          DropdownMenuItem(value: 'IMAGE', child: Text('IMAGE')),
                        ],
                        onChanged: (value) {
                          setState(() => _selectedCategory = value);
                          _loadSeries();
                        },
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: DropdownButtonFormField<String>(
                        decoration: const InputDecoration(
                          labelText: '상태',
                          border: OutlineInputBorder(),
                        ),
                        value: _selectedStatus,
                        items: const [
                          DropdownMenuItem(value: null, child: Text('전체')),
                          DropdownMenuItem(value: 'ongoing', child: Text('연재중')),
                          DropdownMenuItem(value: 'completed', child: Text('완결')),
                        ],
                        onChanged: (value) {
                          setState(() => _selectedStatus = value);
                          _loadSeries();
                        },
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

          // 시리즈 목록
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _seriesList.isEmpty
                ? const Center(child: Text('시리즈가 없습니다'))
                : ListView.builder(
              itemCount: _seriesList.length,
              itemBuilder: (context, index) {
                final series = _seriesList[index];
                return Card(
                  margin: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                  child: ListTile(
                    leading: Container(
                      width: 50,
                      height: 70,
                      color: _getCategoryColor(series['category']),
                      child: Center(
                        child: Text(
                          series['category'] ?? '',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                          ),
                          textAlign: TextAlign.center,
                        ),
                      ),
                    ),
                    title: Text(
                      series['series_name'] ?? '',
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('작가: ${series['author'] ?? ''}'),
                        Text('권수: ${series['available_volumes'] ?? 0}권'),
                        Text('상태: ${series['status'] ?? ''}'),
                      ],
                    ),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        IconButton(
                          icon: const Icon(Icons.edit, color: Colors.blue),
                          onPressed: () => _showEditSeriesDialog(series),
                          tooltip: '수정',
                        ),
                        IconButton(
                          icon: const Icon(Icons.delete, color: Colors.red),
                          onPressed: () => _deleteSeries(
                            series['series_id'],
                            series['series_name'],
                          ),
                          tooltip: '삭제',
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Color _getCategoryColor(String? category) {
    switch (category) {
      case 'MARVEL':
        return Colors.red;
      case 'DC':
        return Colors.blue;
      case 'IMAGE':
        return Colors.purple;
      default:
        return Colors.grey;
    }
  }
}

// ============================================
// 시리즈 수정 다이얼로그
// ============================================
class _EditSeriesDialog extends StatefulWidget {
  final dynamic series;
  final VoidCallback onSeriesUpdated;

  const _EditSeriesDialog({
    required this.series,
    required this.onSeriesUpdated,
  });

  @override
  State<_EditSeriesDialog> createState() => _EditSeriesDialogState();
}

class _EditSeriesDialogState extends State<_EditSeriesDialog> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _seriesNameController;
  late TextEditingController _seriesNameEnController;
  late TextEditingController _authorController;
  late TextEditingController _descriptionController;
  late String _selectedCategory;
  late String _selectedStatus;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _seriesNameController = TextEditingController(text: widget.series['series_name']);
    _seriesNameEnController = TextEditingController(text: widget.series['series_name_en'] ?? '');
    _authorController = TextEditingController(text: widget.series['author']);
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

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSubmitting = true);

    try {
      final response = await AdminApiService.updateSeries(
        seriesId: widget.series['series_id'],
        seriesName: _seriesNameController.text,
        seriesNameEn: _seriesNameEnController.text.isEmpty ? null : _seriesNameEnController.text,
        author: _authorController.text,
        category: _selectedCategory,
        description: _descriptionController.text.isEmpty ? null : _descriptionController.text,
        status: _selectedStatus,
      );

      if (mounted) {
        if (response['success']) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('시리즈가 수정되었습니다')),
          );
          Navigator.pop(context);
          widget.onSeriesUpdated();
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(response['message'] ?? 'Failed')),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isSubmitting = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text('시리즈 수정'),
      content: SingleChildScrollView(
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextFormField(
                controller: _seriesNameController,
                decoration: const InputDecoration(labelText: '시리즈명 *'),
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              TextFormField(
                controller: _seriesNameEnController,
                decoration: const InputDecoration(labelText: '영문명'),
              ),
              TextFormField(
                controller: _authorController,
                decoration: const InputDecoration(labelText: '작가 *'),
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              DropdownButtonFormField<String>(
                value: _selectedCategory,
                decoration: const InputDecoration(labelText: '카테고리'),
                items: const [
                  DropdownMenuItem(value: 'MARVEL', child: Text('MARVEL')),
                  DropdownMenuItem(value: 'DC', child: Text('DC')),
                  DropdownMenuItem(value: 'IMAGE', child: Text('IMAGE')),
                ],
                onChanged: (v) => setState(() => _selectedCategory = v!),
              ),
              DropdownButtonFormField<String>(
                value: _selectedStatus,
                decoration: const InputDecoration(labelText: '상태'),
                items: const [
                  DropdownMenuItem(value: 'ongoing', child: Text('연재중')),
                  DropdownMenuItem(value: 'completed', child: Text('완결')),
                ],
                onChanged: (v) => setState(() => _selectedStatus = v!),
              ),
              TextFormField(
                controller: _descriptionController,
                decoration: const InputDecoration(labelText: '설명'),
                maxLines: 3,
              ),
            ],
          ),
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context),
          child: const Text('취소'),
        ),
        ElevatedButton(
          onPressed: _isSubmitting ? null : _submitForm,
          child: _isSubmitting
              ? const SizedBox(
            width: 20,
            height: 20,
            child: CircularProgressIndicator(strokeWidth: 2),
          )
              : const Text('수정'),
        ),
      ],
    );
  }
}