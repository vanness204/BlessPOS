
import 'package:flutter/material.dart';
import 'package:mobile_desktop/features/auth/presentation/pages/pin_page.dart';

const Color employeeNavy = Color(0xFF142D4E);
const Color employeeTeal = Color(0xFF0D9488);
const Color employeeBg = Color(0xFFF4F7FA);

class EmployeeSelectionPage extends StatefulWidget {
  final Map<String, String> outlet;

  const EmployeeSelectionPage({
    super.key,
    required this.outlet,
  });

  @override
  State<EmployeeSelectionPage> createState() =>
      _EmployeeSelectionPageState();
}

class _EmployeeSelectionPageState
    extends State<EmployeeSelectionPage> {
  String? selectedEmployee;

  final List<Map<String, String>> employees = [
    {'id': '1', 'name': 'Andi Saputra', 'position': 'Kasir'},
    {'id': '2', 'name': 'Budi Santoso', 'position': 'Supervisor'},
    {'id': '3', 'name': 'Siti Rahma', 'position': 'Pelayan'},
    {'id': '4', 'name': 'Dedi Kurniawan', 'position': 'Kitchen Staff'},
  ];

  void continueSelection() {
    if (selectedEmployee == null) return;

    final employee = employees.firstWhere(
      (item) => item['id'] == selectedEmployee,
    );

    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => PinPage(
          outlet: widget.outlet,
          employee: employee,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: employeeBg,
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
                    Icons.people_alt_rounded,
                    color: employeeTeal,
                    size: 42,
                  ),
                  const SizedBox(height: 24),
                  const Text(
                    'Pilih Karyawan',
                    style: TextStyle(
                      fontSize: 28,
                      fontWeight: FontWeight.bold,
                      color: employeeNavy,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Pilih akun karyawan untuk melanjutkan di ${widget.outlet['name']}.',
                    style: const TextStyle(
                      color: Colors.black54,
                      fontSize: 14,
                    ),
                  ),
                  const SizedBox(height: 28),
                  const Text(
                    'DAFTAR KARYAWAN',
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
                      itemCount: employees.length,
                      separatorBuilder: (context, index) =>
                          const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final employee = employees[index];
                        final isSelected =
                            selectedEmployee == employee['id'];

                        return InkWell(
                          onTap: () {
                            setState(() {
                              selectedEmployee = employee['id'];
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
                                    ? employeeTeal
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
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: const Icon(
                                    Icons.person_rounded,
                                    color: employeeTeal,
                                  ),
                                ),
                                const SizedBox(width: 14),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        employee['name']!,
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 16,
                                          color: employeeNavy,
                                        ),
                                      ),
                                      const SizedBox(height: 5),
                                      Text(
                                        employee['position']!,
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
                                      ? employeeTeal
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
                      onPressed: selectedEmployee == null
                          ? null
                          : continueSelection,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: employeeTeal,
                        foregroundColor: Colors.white,
                        disabledBackgroundColor: Colors.grey.shade300,
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
