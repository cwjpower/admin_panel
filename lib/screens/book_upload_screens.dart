// lib/screens/book_upload_screens.dart
import 'dart:io';
import 'package:file_picker/file_picker.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter/material.dart';
import '../services/admin_api_service.dart';

// ============================================
// 시리즈 관리 화면
// ============================================
class SeriesManageScreen extends StatefulWidget {
  const SeriesManageScreen({Key? key}) : super(key: key);

  @override
  State<SeriesManageScreen> createState() => _SeriesManageScreenState();
}

class _SeriesManageScreenState extends State<SeriesManageScreen> {
  List<dynamic> _seriesList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadSeries();
  }

  Future<void> _loadSeries() async {
    setState(() => _isLoading = true);

    try {
      final response = await AdminApiService.getSeriesList();

      if (response['success']) {
        setState(() {
          _seriesList = response['result'] ?? [];
          _isLoading = false;
        });
      } else {
        setState(() => _isLoading = false);
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(response['message'] ?? 'Failed')),
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

  void _showAddSeriesDialog() {
    showDialog(
      context: context,
      builder: (context) => AddSeriesDialog(onSeriesAdded: _loadSeries),
    );
  }

  void _showEditSeriesDialog(dynamic series) {
    showDialog(
      context: context,
      builder: (context) => EditSeriesDialog(
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
        title: const Text('시리즈 관리'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadSeries,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _seriesList.isEmpty
          ? const Center(child: Text('시리즈가 없습니다'))
          : ListView.builder(
        itemCount: _seriesList.length,
        itemBuilder: (context, index) {
          final series = _seriesList[index];
          return Card(
            margin: const EdgeInsets.all(8),
            child: ListTile(
              title: Text(series['series_name'] ?? ''),
              subtitle: Text('${series['author'] ?? ''} · ${series['category'] ?? ''}'),
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
                  IconButton(
                    icon: const Icon(Icons.arrow_forward),
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => VolumeManageScreen(
                            seriesId: series['series_id'],
                            seriesName: series['series_name'],
                          ),
                        ),
                      );
                    },
                    tooltip: '권 관리',
                  ),
                ],
              ),
            ),
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _showAddSeriesDialog,
        child: const Icon(Icons.add),
      ),
    );
  }
}

// ============================================
// 시리즈 추가 다이얼로그
// ============================================
class AddSeriesDialog extends StatefulWidget {
  final VoidCallback onSeriesAdded;

  const AddSeriesDialog({Key? key, required this.onSeriesAdded}) : super(key: key);

  @override
  State<AddSeriesDialog> createState() => _AddSeriesDialogState();
}

class _AddSeriesDialogState extends State<AddSeriesDialog> {
  final _formKey = GlobalKey<FormState>();
  final _seriesNameController = TextEditingController();
  final _seriesNameEnController = TextEditingController();
  final _authorController = TextEditingController();
  final _descriptionController = TextEditingController();
  String _selectedCategory = 'MARVEL';
  String _selectedStatus = 'ongoing';
  bool _isSubmitting = false;

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
      final response = await AdminApiService.addSeries(
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
            const SnackBar(content: Text('시리즈가 추가되었습니다')),
          );
          Navigator.pop(context);
          widget.onSeriesAdded();
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
      title: const Text('새 시리즈 추가'),
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
              : const Text('추가'),
        ),
      ],
    );
  }
}

// ============================================
// 시리즈 수정 다이얼로그
// ============================================
class EditSeriesDialog extends StatefulWidget {
  final dynamic series;
  final VoidCallback onSeriesUpdated;

  const EditSeriesDialog({
    Key? key,
    required this.series,
    required this.onSeriesUpdated,
  }) : super(key: key);

  @override
  State<EditSeriesDialog> createState() => _EditSeriesDialogState();
}

class _EditSeriesDialogState extends State<EditSeriesDialog> {
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

// ============================================
// 권 관리 화면
// ============================================
class VolumeManageScreen extends StatefulWidget {
  final int seriesId;
  final String seriesName;

  const VolumeManageScreen({
    Key? key,
    required this.seriesId,
    required this.seriesName,
  }) : super(key: key);

  @override
  State<VolumeManageScreen> createState() => _VolumeManageScreenState();
}

class _VolumeManageScreenState extends State<VolumeManageScreen> {
  List<dynamic> _volumesList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadVolumes();
  }

  Future<void> _loadVolumes() async {
    setState(() => _isLoading = true);

    try {
      final response = await AdminApiService.getVolumesList(
        seriesId: widget.seriesId,
      );

      if (response['success']) {
        setState(() {
          _volumesList = response['result'] ?? [];
          _isLoading = false;
        });
      } else {
        setState(() => _isLoading = false);
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(response['message'] ?? 'Failed')),
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

  // 커버 이미지 업로드
  Future<void> _uploadCover(int volumeId) async {
    final ImagePicker picker = ImagePicker();
    final XFile? image = await picker.pickImage(source: ImageSource.gallery);

    if (image == null) return;

    if (!mounted) return;
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(child: CircularProgressIndicator()),
    );

    final result = await AdminApiService.uploadVolumeCover(
      volumeId,
      File(image.path),
    );

    if (!mounted) return;
    Navigator.pop(context);

    if (result['code'] == 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('커버 업로드 성공!')),
      );
      _loadVolumes();
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('업로드 실패: ${result['msg']}')),
      );
    }
  }

  // 페이지 ZIP 업로드
  Future<void> _uploadPages(int volumeId) async {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['zip'],
    );

    if (result == null || result.files.single.path == null) return;

    if (!mounted) return;
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            CircularProgressIndicator(),
            SizedBox(height: 16),
            Text('ZIP 파일 업로드 중...', style: TextStyle(color: Colors.white)),
          ],
        ),
      ),
    );

    final uploadResult = await AdminApiService.uploadPagesZip(
      volumeId,
      File(result.files.single.path!),
    );

    if (!mounted) return;
    Navigator.pop(context);

    if (uploadResult['code'] == 0) {
      final pageCount = uploadResult['data']['page_count'];
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('페이지 업로드 성공! ($pageCount 페이지)')),
      );
      _loadVolumes();
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('업로드 실패: ${uploadResult['msg']}')),
      );
    }
  }

  void _showAddVolumeDialog() {
    showDialog(
      context: context,
      builder: (context) => AddVolumeDialog(
        seriesId: widget.seriesId,
        onVolumeAdded: _loadVolumes,
      ),
    );
  }

  void _showEditVolumeDialog(dynamic volume) {
    showDialog(
      context: context,
      builder: (context) => EditVolumeDialog(
        volume: volume,
        onVolumeUpdated: _loadVolumes,
      ),
    );
  }

  Future<void> _deleteVolume(int volumeId, String volumeTitle) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('권 삭제'),
        content: Text('$volumeTitle을(를) 삭제하시겠습니까?'),
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
        final response = await AdminApiService.deleteVolume(volumeId: volumeId);

        if (mounted) {
          if (response['success']) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('권이 삭제되었습니다')),
            );
            _loadVolumes();
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
        title: Text('${widget.seriesName} - 권 관리'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadVolumes,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _volumesList.isEmpty
          ? const Center(child: Text('권이 없습니다'))
          : ListView.builder(
        itemCount: _volumesList.length,
        itemBuilder: (context, index) {
          final volume = _volumesList[index];
          return Card(
            margin: const EdgeInsets.all(8),
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      // 커버 이미지 썸네일
                      Container(
                        width: 80,
                        height: 100,
                        decoration: BoxDecoration(
                          border: Border.all(color: Colors.grey.shade300),
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: volume['cover_image'] != null
                            ? ClipRRect(
                          borderRadius: BorderRadius.circular(4),
                          child: Image.network(
                            'http://34.64.84.117:8081/uploads/volume_covers/${volume['cover_image']}',
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) {
                              return const Center(
                                child: Icon(Icons.broken_image, color: Colors.grey),
                              );
                            },
                            loadingBuilder: (context, child, loadingProgress) {
                              if (loadingProgress == null) return child;
                              return Center(
                                child: CircularProgressIndicator(
                                  value: loadingProgress.expectedTotalBytes != null
                                      ? loadingProgress.cumulativeBytesLoaded /
                                      loadingProgress.expectedTotalBytes!
                                      : null,
                                ),
                              );
                            },
                          ),
                        )
                            : const Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.image_not_supported, color: Colors.grey),
                              SizedBox(height: 4),
                              Text('커버 없음', style: TextStyle(fontSize: 10, color: Colors.grey)),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              volume['volume_title'] ?? '',
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 16,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text('권 번호: ${volume['volume_number']}'),
                            Text('₩${volume['price']} · ${volume['status']}'),
                            Text(
                              '페이지: ${volume['page_count'] ?? 0}페이지',
                              style: TextStyle(
                                color: (volume['page_count'] ?? 0) > 0 ? Colors.green : Colors.grey,
                                fontWeight: (volume['page_count'] ?? 0) > 0 ? FontWeight.bold : FontWeight.normal,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Column(
                        children: [
                          IconButton(
                            icon: const Icon(Icons.edit, color: Colors.blue),
                            onPressed: () => _showEditVolumeDialog(volume),
                            tooltip: '수정',
                          ),
                          IconButton(
                            icon: const Icon(Icons.delete, color: Colors.red),
                            onPressed: () => _deleteVolume(
                              volume['volume_id'],
                              volume['volume_title'] ?? '',
                            ),
                            tooltip: '삭제',
                          ),
                        ],
                      ),
                    ],
                  ),
                  const Divider(),
                  // 업로드 버튼들
                  Row(
                    children: [
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: () => _uploadCover(volume['volume_id']),
                          icon: const Icon(Icons.image),
                          label: Text(volume['cover_image'] != null ? '커버 재업로드' : '커버 업로드'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.green,
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: () => _uploadPages(volume['volume_id']),
                          icon: const Icon(Icons.folder_zip),
                          label: Text((volume['page_count'] ?? 0) > 0
                              ? '페이지 재업로드 (${volume['page_count']})'
                              : '페이지 업로드'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.orange,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _showAddVolumeDialog,
        child: const Icon(Icons.add),
      ),
    );
  }
}

// ============================================
// 권 추가 다이얼로그
// ============================================
class AddVolumeDialog extends StatefulWidget {
  final int seriesId;
  final VoidCallback onVolumeAdded;

  const AddVolumeDialog({
    Key? key,
    required this.seriesId,
    required this.onVolumeAdded,
  }) : super(key: key);

  @override
  State<AddVolumeDialog> createState() => _AddVolumeDialogState();
}

class _AddVolumeDialogState extends State<AddVolumeDialog> {
  final _formKey = GlobalKey<FormState>();
  final _volumeNumberController = TextEditingController();
  final _volumeTitleController = TextEditingController();
  final _priceController = TextEditingController();
  bool _isFree = false;
  String _selectedStatus = 'published';
  bool _isSubmitting = false;

  @override
  void dispose() {
    _volumeNumberController.dispose();
    _volumeTitleController.dispose();
    _priceController.dispose();
    super.dispose();
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSubmitting = true);

    try {
      final response = await AdminApiService.addVolume(
        seriesId: widget.seriesId,
        volumeNumber: int.parse(_volumeNumberController.text),
        volumeTitle: _volumeTitleController.text,
        price: int.parse(_priceController.text),
        isFree: _isFree,
        status: _selectedStatus,
      );

      if (mounted) {
        if (response['success']) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('권이 추가되었습니다')),
          );
          Navigator.pop(context);
          widget.onVolumeAdded();
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
      title: const Text('새 권 추가'),
      content: SingleChildScrollView(
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextFormField(
                controller: _volumeNumberController,
                decoration: const InputDecoration(labelText: '권 번호 *'),
                keyboardType: TextInputType.number,
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              TextFormField(
                controller: _volumeTitleController,
                decoration: const InputDecoration(labelText: '권 제목 *'),
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              TextFormField(
                controller: _priceController,
                decoration: const InputDecoration(labelText: '가격 *'),
                keyboardType: TextInputType.number,
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              SwitchListTile(
                title: const Text('무료'),
                value: _isFree,
                onChanged: (v) => setState(() => _isFree = v),
              ),
              DropdownButtonFormField<String>(
                value: _selectedStatus,
                decoration: const InputDecoration(labelText: '상태'),
                items: const [
                  DropdownMenuItem(value: 'published', child: Text('출간')),
                  DropdownMenuItem(value: 'draft', child: Text('초안')),
                ],
                onChanged: (v) => setState(() => _selectedStatus = v!),
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
              : const Text('추가'),
        ),
      ],
    );
  }
}

// ============================================
// 권 수정 다이얼로그
// ============================================
class EditVolumeDialog extends StatefulWidget {
  final dynamic volume;
  final VoidCallback onVolumeUpdated;

  const EditVolumeDialog({
    Key? key,
    required this.volume,
    required this.onVolumeUpdated,
  }) : super(key: key);

  @override
  State<EditVolumeDialog> createState() => _EditVolumeDialogState();
}

class _EditVolumeDialogState extends State<EditVolumeDialog> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _volumeNumberController;
  late TextEditingController _volumeTitleController;
  late TextEditingController _priceController;
  late bool _isFree;
  late String _selectedStatus;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _volumeNumberController = TextEditingController(
      text: widget.volume['volume_number'].toString(),
    );
    _volumeTitleController = TextEditingController(
      text: widget.volume['volume_title'],
    );
    _priceController = TextEditingController(
      text: widget.volume['price'].toString(),
    );
    _isFree = widget.volume['is_free'] == 1;
    _selectedStatus = widget.volume['status'] ?? 'published';
  }

  @override
  void dispose() {
    _volumeNumberController.dispose();
    _volumeTitleController.dispose();
    _priceController.dispose();
    super.dispose();
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSubmitting = true);

    try {
      final response = await AdminApiService.updateVolume(
        volumeId: widget.volume['volume_id'],
        volumeNumber: int.parse(_volumeNumberController.text),
        volumeTitle: _volumeTitleController.text,
        price: int.parse(_priceController.text),
        isFree: _isFree,
        status: _selectedStatus,
      );

      if (mounted) {
        if (response['success']) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('권이 수정되었습니다')),
          );
          Navigator.pop(context);
          widget.onVolumeUpdated();
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
      title: const Text('권 수정'),
      content: SingleChildScrollView(
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextFormField(
                controller: _volumeNumberController,
                decoration: const InputDecoration(labelText: '권 번호 *'),
                keyboardType: TextInputType.number,
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              TextFormField(
                controller: _volumeTitleController,
                decoration: const InputDecoration(labelText: '권 제목 *'),
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              TextFormField(
                controller: _priceController,
                decoration: const InputDecoration(labelText: '가격 *'),
                keyboardType: TextInputType.number,
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              SwitchListTile(
                title: const Text('무료'),
                value: _isFree,
                onChanged: (v) => setState(() => _isFree = v),
              ),
              DropdownButtonFormField<String>(
                value: _selectedStatus,
                decoration: const InputDecoration(labelText: '상태'),
                items: const [
                  DropdownMenuItem(value: 'published', child: Text('출간')),
                  DropdownMenuItem(value: 'draft', child: Text('초안')),
                ],
                onChanged: (v) => setState(() => _selectedStatus = v!),
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