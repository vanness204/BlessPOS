
import 'package:flutter/material.dart';

const Color navy = Color(0xFF142D4E);
const Color teal = Color(0xFF0D9488);
const Color pageBg = Color(0xFFF4F7FA);

class OutletSelectionPage extends StatefulWidget {
  const OutletSelectionPage({super.key});

  @override
  State<OutletSelectionPage> createState() =>
      _OutletSelectionPageState();
}

class _OutletSelectionPageState extends State<OutletSelectionPage> {
  String? selectedOutlet;

  final List<Map<String, String>> outlets = [
    {
      'id': '1',
      'name': 'Bless Cafe',
      'address': 'Palembang',
      'type': 'Restoran',
    },
    {
      'id': '2',
      'name': 'Bless Foodcourt',
      'address': 'Palembang',
      'type': 'Foodcourt',
    },
    {
      'id': '3',
      'name': 'Bless Store',
      'address': 'Palembang',
      'type': 'Retail',
    },
  ];

  void continueSelection() {
    if (selectedOutlet == null) return;

    final outlet = outlets.firstWhere(
          (item) => item['id'] == selectedOutlet,
    );

    Navigator.pop(context, outlet);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: pageBg,
      body: SafeArea(
        child: Center(
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 650),
            child: Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: 20),
                  const Icon(
                    Icons.storefront_rounded,
                    color: teal,
                    size: 42,
                  ),
                  const SizedBox(height: 24),
                  const Text(
                    'Pilih Outlet',
                    style: TextStyle(
                      fontSize: 28,
                      fontWeight: FontWeight.bold,
                      color: navy,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Pilih outlet yang ingin digunakan untuk memulai operasional.',
                    style: TextStyle(
                      color: Colors.black54,
                      fontSize: 14,
                    ),
                  ),
                  const SizedBox(height: 28),
                  const Text(
                    'DAFTAR OUTLET',
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      color: Colors.black54,
                      letterSpacing: 1,
                    ),
                  ),
                  const SizedBox(height: 14),
                  Expanded(
                    child: ListView.separated(
                      itemCount: outlets.length,
                      separatorBuilder: (context, index) =>
                      const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final outlet = outlets[index];
                        final isSelected =
                            selectedOutlet == outlet['id'];

                        return InkWell(
                          onTap: () {
                            setState(() {
                              selectedOutlet = outlet['id'];
                            });
                          },
                          borderRadius: BorderRadius.circular(16),
                          child: Container(
                            padding: const EdgeInsets.all(18),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                              border: Border.all(
                                color: isSelected
                                    ? teal
                                    : Colors.grey.shade200,
                                width: isSelected ? 2 : 1,
                              ),
                            ),
                            child: Row(
                              children: [
                                Container(
                                  width: 48,
                                  height: 48,
                                  decoration: BoxDecoration(
                                    color: const Color(0xFFE6F4F2),
                                    borderRadius:
                                    BorderRadius.circular(12),
                                  ),
                                  child: const Icon(
                                    Icons.store_rounded,
                                    color: teal,
                                  ),
                                ),
                                const SizedBox(width: 14),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                    CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        outlet['name']!,
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 16,
                                          color: navy,
                                        ),
                                      ),
                                      const SizedBox(height: 5),
                                      Text(
                                        '${outlet['type']} • ${outlet['address']}',
                                        style: const TextStyle(
                                          fontSize: 12,
                                          color: Colors.black54,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                                Icon(
                                  isSelected
                                      ? Icons.check_circle
                                      : Icons.circle_outlined,
                                  color: isSelected
                                      ? teal
                                      : Colors.grey,
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      onPressed: selectedOutlet == null
                          ? null
                          : continueSelection,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: teal,
                        foregroundColor: Colors.white,
                        disabledBackgroundColor:
                        Colors.grey.shade300,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                      child: const Text(
                        'Lanjutkan',
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
