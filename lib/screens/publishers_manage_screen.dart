// admin_app/lib/screens/publisher_manage_screen.dart

import 'package:flutter/material.dart';
import '../services/admin_api_service.dart';

class PublishersManageScreen extends StatefulWidget {  // Publishers로 변경!
  const PublishersManageScreen({Key? key}) : super(key: key);

  @override
  State<PublishersManageScreen> createState() => _PublishersManageScreenState();  // 여기도!
}

class _PublishersManageScreenState extends State<PublishersManageScreen> {  // Publishers로 변경!
  List<dynamic> publishers = [];
  bool isLoading = false;
  int currentPage = 1;
  int totalPages = 1;
  final searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _loadPublishers();
  }

  Future<void> _loadPublishers({String? search}) async {
    setState(() => isLoading = true);
    try {
      final result = await AdminApiService.fetchPublishers(
        page: currentPage,
        search: search,
      );
      if (result['success']) {
        setState(() {
          publishers = result['data'];
          totalPages = result['pagination']['total_pages'];
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('출판사 목록 로딩 실패: $e')),
      );
    } finally {
      setState(() => isLoading = false);
    }
  }

  void _showAddDialog() {
    final nameController = TextEditingController();
    final codeController = TextEditingController();
    final nameKoController = TextEditingController();
    final contactNameController = TextEditingController();
    final emailController = TextEditingController();
    final phoneController = TextEditingController();
    final rateController = TextEditingController(text: '30.00');
    final descController = TextEditingController();
    final websiteController = TextEditingController();
    String selectedStatus = 'active';

    showDialog(
      context: context,
      builder: (context) => StatefulBuilder(
        builder: (context, setDialogState) => AlertDialog(
          title: const Text('출판사 추가'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: nameController,
                  decoration: const InputDecoration(
                    labelText: '출판사명 (영문) *',
                    border: OutlineInputBorder(),
                    hintText: 'Marvel Comics',
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: codeController,
                  decoration: const InputDecoration(
                    labelText: '출판사 코드 *',
                    border: OutlineInputBorder(),
                    hintText: 'MARVEL',
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: nameKoController,
                  decoration: const InputDecoration(
                    labelText: '출판사명 (한글)',
                    border: OutlineInputBorder(),
                    hintText: '마블 코믹스',
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: contactNameController,
                  decoration: const InputDecoration(
                    labelText: '담당자명',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: emailController,
                  decoration: const InputDecoration(
                    labelText: '이메일',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.emailAddress,
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: phoneController,
                  decoration: const InputDecoration(
                    labelText: '전화번호',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.phone,
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: rateController,
                  decoration: const InputDecoration(
                    labelText: '수수료율 (%)',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.number,
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  value: selectedStatus,
                  decoration: const InputDecoration(
                    labelText: '상태',
                    border: OutlineInputBorder(),
                  ),
                  items: const [
                    DropdownMenuItem(value: 'pending', child: Text('대기')),
                    DropdownMenuItem(value: 'active', child: Text('활성')),
                    DropdownMenuItem(value: 'suspended', child: Text('정지')),
                  ],
                  onChanged: (value) {
                    setDialogState(() => selectedStatus = value!);
                  },
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: descController,
                  decoration: const InputDecoration(
                    labelText: '설명',
                    border: OutlineInputBorder(),
                  ),
                  maxLines: 3,
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: websiteController,
                  decoration: const InputDecoration(
                    labelText: '웹사이트',
                    border: OutlineInputBorder(),
                  ),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('취소'),
            ),
            ElevatedButton(
              onPressed: () async {
                if (nameController.text.trim().isEmpty) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('출판사명을 입력해주세요')),
                  );
                  return;
                }
                if (codeController.text.trim().isEmpty) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('출판사 코드를 입력해주세요')),
                  );
                  return;
                }

                try {
                  final result = await AdminApiService.addPublisher(
                    publisherName: nameController.text.trim(),
                    publisherCode: codeController.text.trim(),
                    publisherNameKo: nameKoController.text.trim(),
                    contactName: contactNameController.text.trim(),
                    contactEmail: emailController.text.trim(),
                    contactPhone: phoneController.text.trim(),
                    commissionRate: double.tryParse(rateController.text),
                    description: descController.text.trim(),
                    website: websiteController.text.trim(),
                    status: selectedStatus,
                  );

                  if (result['success']) {
                    Navigator.pop(context);
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('출판사가 추가되었습니다')),
                    );
                    _loadPublishers();
                  } else {
                    throw Exception(result['message']);
                  }
                } catch (e) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('추가 실패: $e')),
                  );
                }
              },
              child: const Text('추가'),
            ),
          ],
        ),
      ),
    );
  }

  void _showEditDialog(dynamic publisher) {
    final nameController = TextEditingController(text: publisher['publisher_name']);
    final codeController = TextEditingController(text: publisher['publisher_code']);
    final nameKoController = TextEditingController(text: publisher['publisher_name_ko'] ?? '');
    final contactNameController = TextEditingController(text: publisher['contact_name'] ?? '');
    final emailController = TextEditingController(text: publisher['contact_email'] ?? '');
    final phoneController = TextEditingController(text: publisher['contact_phone'] ?? '');
    final rateController = TextEditingController(text: publisher['commission_rate'] ?? '30.00');
    final descController = TextEditingController(text: publisher['description'] ?? '');
    final websiteController = TextEditingController(text: publisher['website'] ?? '');
    String selectedStatus = publisher['status'] ?? 'active';

    showDialog(
      context: context,
      builder: (context) => StatefulBuilder(
        builder: (context, setDialogState) => AlertDialog(
          title: const Text('출판사 수정'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: nameController,
                  decoration: const InputDecoration(
                    labelText: '출판사명 (영문) *',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: codeController,
                  decoration: const InputDecoration(
                    labelText: '출판사 코드 *',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: nameKoController,
                  decoration: const InputDecoration(
                    labelText: '출판사명 (한글)',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: contactNameController,
                  decoration: const InputDecoration(
                    labelText: '담당자명',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: emailController,
                  decoration: const InputDecoration(
                    labelText: '이메일',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.emailAddress,
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: phoneController,
                  decoration: const InputDecoration(
                    labelText: '전화번호',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.phone,
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: rateController,
                  decoration: const InputDecoration(
                    labelText: '수수료율 (%)',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.number,
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  value: selectedStatus,
                  decoration: const InputDecoration(
                    labelText: '상태',
                    border: OutlineInputBorder(),
                  ),
                  items: const [
                    DropdownMenuItem(value: 'pending', child: Text('대기')),
                    DropdownMenuItem(value: 'active', child: Text('활성')),
                    DropdownMenuItem(value: 'suspended', child: Text('정지')),
                  ],
                  onChanged: (value) {
                    setDialogState(() => selectedStatus = value!);
                  },
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: descController,
                  decoration: const InputDecoration(
                    labelText: '설명',
                    border: OutlineInputBorder(),
                  ),
                  maxLines: 3,
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: websiteController,
                  decoration: const InputDecoration(
                    labelText: '웹사이트',
                    border: OutlineInputBorder(),
                  ),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('취소'),
            ),
            ElevatedButton(
              onPressed: () async {
                if (nameController.text.trim().isEmpty) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('출판사명을 입력해주세요')),
                  );
                  return;
                }
                if (codeController.text.trim().isEmpty) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('출판사 코드를 입력해주세요')),
                  );
                  return;
                }

                try {
                  final result = await AdminApiService.updatePublisher(
                    publisherId: publisher['publisher_id'],
                    publisherName: nameController.text.trim(),
                    publisherCode: codeController.text.trim(),
                    publisherNameKo: nameKoController.text.trim(),
                    contactName: contactNameController.text.trim(),
                    contactEmail: emailController.text.trim(),
                    contactPhone: phoneController.text.trim(),
                    commissionRate: double.tryParse(rateController.text),
                    description: descController.text.trim(),
                    website: websiteController.text.trim(),
                    status: selectedStatus,
                  );

                  if (result['success']) {
                    Navigator.pop(context);
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('출판사 정보가 수정되었습니다')),
                    );
                    _loadPublishers();
                  } else {
                    throw Exception(result['message']);
                  }
                } catch (e) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('수정 실패: $e')),
                  );
                }
              },
              child: const Text('수정'),
            ),
          ],
        ),
      ),
    );
  }

  void _deletePublisher(dynamic publisher) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('출판사 삭제'),
        content: Text('${publisher['publisher_name']}을(를) 삭제하시겠습니까?\n\n'
            '※ 이 출판사의 시리즈가 있으면 삭제할 수 없습니다.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('취소'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            onPressed: () async {
              try {
                final result = await AdminApiService.deletePublisher(
                  publisher['publisher_id'],
                );

                if (result['success']) {
                  Navigator.pop(context);
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('출판사가 삭제되었습니다')),
                  );
                  _loadPublishers();
                } else {
                  Navigator.pop(context);
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text(result['message'])),
                  );
                }
              } catch (e) {
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('삭제 실패: $e')),
                );
              }
            },
            child: const Text('삭제'),
          ),
        ],
      ),
    );
  }

  String _getStatusText(String status) {
    switch (status) {
      case 'pending':
        return '대기';
      case 'active':
        return '활성';
      case 'suspended':
        return '정지';
      default:
        return status;
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'pending':
        return Colors.orange;
      case 'active':
        return Colors.green;
      case 'suspended':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('출판사 관리'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: _showAddDialog,
            tooltip: '출판사 추가',
          ),
        ],
      ),
      body: Column(
        children: [
          // 검색바
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: searchController,
              decoration: InputDecoration(
                hintText: '출판사 검색...',
                prefixIcon: const Icon(Icons.search),
                border: const OutlineInputBorder(),
                suffixIcon: searchController.text.isNotEmpty
                    ? IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    searchController.clear();
                    _loadPublishers();
                  },
                )
                    : null,
              ),
              onSubmitted: (value) => _loadPublishers(search: value),
            ),
          ),

          // 출판사 목록
          Expanded(
            child: isLoading
                ? const Center(child: CircularProgressIndicator())
                : publishers.isEmpty
                ? const Center(child: Text('출판사가 없습니다'))
                : ListView.builder(
              itemCount: publishers.length,
              itemBuilder: (context, index) {
                final publisher = publishers[index];
                return Card(
                  margin: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getStatusColor(publisher['status']),
                      child: Text(
                        publisher['publisher_code']?.substring(0, 1) ?? 'P',
                        style: const TextStyle(color: Colors.white),
                      ),
                    ),
                    title: Row(
                      children: [
                        Text(
                          publisher['publisher_name'],
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 8,
                            vertical: 2,
                          ),
                          decoration: BoxDecoration(
                            color: _getStatusColor(publisher['status']),
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Text(
                            _getStatusText(publisher['status']),
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 12,
                            ),
                          ),
                        ),
                      ],
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('코드: ${publisher['publisher_code']}'),
                        if (publisher['publisher_name_ko'] != null)
                          Text(publisher['publisher_name_ko']),
                        if (publisher['contact_email'] != null)
                          Text(
                            publisher['contact_email'],
                            style: TextStyle(
                              color: Colors.blue[700],
                              fontSize: 12,
                            ),
                          ),
                        Text(
                          '수수료: ${publisher['commission_rate']}%',
                          style: const TextStyle(fontSize: 12),
                        ),
                      ],
                    ),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        IconButton(
                          icon: const Icon(Icons.edit),
                          onPressed: () => _showEditDialog(publisher),
                          tooltip: '수정',
                        ),
                        IconButton(
                          icon: const Icon(Icons.delete,
                              color: Colors.red),
                          onPressed: () => _deletePublisher(publisher),
                          tooltip: '삭제',
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),

          // 페이지네이션
          if (totalPages > 1)
            Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  IconButton(
                    icon: const Icon(Icons.chevron_left),
                    onPressed: currentPage > 1
                        ? () {
                      setState(() => currentPage--);
                      _loadPublishers();
                    }
                        : null,
                  ),
                  Text('$currentPage / $totalPages'),
                  IconButton(
                    icon: const Icon(Icons.chevron_right),
                    onPressed: currentPage < totalPages
                        ? () {
                      setState(() => currentPage++);
                      _loadPublishers();
                    }
                        : null,
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}