
import 'package:flutter/material.dart';
import 'package:mobile_desktop/features/outlets/presentation/pages/outlet_selection_page.dart';
import 'package:mobile_desktop/features/employees/presentation/pages/employee_selection_page.dart';

const navy = Color(0xFF142D4E);
const teal = Color(0xFF0D9488);
const pageBg = Color(0xFFF4F7FA);

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final emailController = TextEditingController();
  final passwordController = TextEditingController();

  bool obscurePassword = true;

  @override
  void dispose() {
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  Future<void> login() async {
    final email = emailController.text.trim();
    final password = passwordController.text;

    if (email.isEmpty || password.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Email dan password wajib diisi.'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    // Login sementara masih berupa simulasi.
    // Nantinya email dan password diverifikasi melalui API Laravel.
    final outlet = await Navigator.push<Map<String, String>>(
      context,
      MaterialPageRoute(
        builder: (context) => const OutletSelectionPage(),
      ),
    );

    if (!mounted || outlet == null) return;

    await Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => EmployeeSelectionPage(outlet: outlet),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: pageBg,
      body: SafeArea(
        child: LayoutBuilder(
          builder: (context, constraints) {
            final isDesktop = constraints.maxWidth >= 850;

            if (isDesktop) {
              return Row(
                children: [
                  Expanded(
                    flex: 5,
                    child: _buildBrandPanel(),
                  ),
                  Expanded(
                    flex: 4,
                    child: _buildLoginPanel(),
                  ),
                ],
              );
            }

            return SingleChildScrollView(
              child: Column(
                children: [
                  _buildMobileBrand(),
                  _buildLoginPanel(),
                ],
              ),
            );
          },
        ),
      ),
    );
  }

  // PANEL BRANDING DESKTOP
  Widget _buildBrandPanel() {
    return Container(
      width: double.infinity,
      height: double.infinity,
      padding: const EdgeInsets.symmetric(
        horizontal: 55,
        vertical: 45,
      ),
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [
            Color(0xFF0B2343),
            Color(0xFF173F69),
            Color(0xFF102D50),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildLogo(light: true),
          const Spacer(),
          const Text(
            'Kelola Bisnis\nLebih Mudah',
            style: TextStyle(
              color: Colors.white,
              fontSize: 42,
              height: 1.2,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 20),
          const Text(
            'Kasir cepat, laporan akurat,\nbisnis makin berkembang.',
            style: TextStyle(
              color: Color(0xFFDCE7F2),
              fontSize: 18,
              height: 1.6,
            ),
          ),
          const SizedBox(height: 45),
          _benefit(Icons.bolt_rounded, 'Transaksi Cepat'),
          _benefit(Icons.bar_chart_rounded, 'Laporan Lengkap'),
          _benefit(Icons.verified_user_outlined, 'Aman & Terpercaya'),
          const Spacer(),
          const Text(
            'Solusi kasir dan manajemen bisnis modern.',
            style: TextStyle(
              color: Color(0xFFB9CADB),
              fontSize: 12,
            ),
          ),
        ],
      ),
    );
  }

  // BRANDING MOBILE
  Widget _buildMobileBrand() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(
        horizontal: 25,
        vertical: 35,
      ),
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [
            navy,
            Color(0xFF20527B),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.vertical(
          bottom: Radius.circular(28),
        ),
      ),
      child: Column(
        children: [
          _buildLogo(light: true),
          const SizedBox(height: 25),
          const Text(
            'Kelola Bisnis Lebih Mudah',
            textAlign: TextAlign.center,
            style: TextStyle(
              color: Colors.white,
              fontSize: 25,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8),
          const Text(
            'Kasir cepat, laporan akurat, bisnis makin berkembang.',
            textAlign: TextAlign.center,
            style: TextStyle(
              color: Color(0xFFDCE7F2),
              fontSize: 13,
            ),
          ),
        ],
      ),
    );
  }

  // LOGO BLESS POS
  Widget _buildLogo({required bool light}) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          width: 55,
          height: 55,
          decoration: BoxDecoration(
            color: teal,
            borderRadius: BorderRadius.circular(15),
          ),
          child: const Icon(
            Icons.point_of_sale_rounded,
            color: Colors.white,
            size: 32,
          ),
        ),
        const SizedBox(width: 15),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'BLESS POS',
              style: TextStyle(
                color: light ? Colors.white : navy,
                fontSize: 24,
                fontWeight: FontWeight.bold,
                letterSpacing: 1,
              ),
            ),
            Text(
              'Modern Point of Sale',
              style: TextStyle(
                color: light
                    ? const Color(0xFFDCE7F2)
                    : Colors.blueGrey,
                fontSize: 12,
              ),
            ),
          ],
        ),
      ],
    );
  }

  // DAFTAR KEUNGGULAN
  Widget _benefit(IconData icon, String label) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 17),
      child: Row(
        children: [
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: teal,
              borderRadius: BorderRadius.circular(50),
            ),
            child: Icon(
              icon,
              color: Colors.white,
              size: 23,
            ),
          ),
          const SizedBox(width: 16),
          Text(
            label,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 16,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  // PANEL LOGIN
  Widget _buildLoginPanel() {
    return Container(
      width: double.infinity,
      color: Colors.white,
      padding: const EdgeInsets.symmetric(
        horizontal: 35,
        vertical: 35,
      ),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 430),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Center(
                child: Container(
                  width: 75,
                  height: 75,
                  decoration: BoxDecoration(
                    color: navy,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Icon(
                    Icons.point_of_sale_rounded,
                    color: Colors.white,
                    size: 43,
                  ),
                ),
              ),
              const SizedBox(height: 18),
              const Text(
                'BLESS POS',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: navy,
                  fontSize: 29,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 5),
              const Text(
                'Sistem Kasir dan Manajemen Bisnis',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: Color(0xFF64748B),
                  fontSize: 13,
                ),
              ),
              const SizedBox(height: 42),
              const Text(
                'Selamat Datang!',
                style: TextStyle(
                  color: navy,
                  fontSize: 27,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              const Text(
                'Silakan masuk ke akun Bless POS Anda.',
                style: TextStyle(
                  color: Color(0xFF64748B),
                  fontSize: 14,
                ),
              ),
              const SizedBox(height: 30),

              _fieldLabel('Email'),
              const SizedBox(height: 9),
              TextField(
                controller: emailController,
                keyboardType: TextInputType.emailAddress,
                textInputAction: TextInputAction.next,
                decoration: _inputDecoration(
                  'Masukkan email Anda',
                  Icons.email_outlined,
                ),
              ),

              const SizedBox(height: 22),

              _fieldLabel('Password'),
              const SizedBox(height: 9),
              TextField(
                controller: passwordController,
                obscureText: obscurePassword,
                onSubmitted: (_) => login(),
                decoration: _inputDecoration(
                  'Masukkan password',
                  Icons.lock_outline,
                ).copyWith(
                  suffixIcon: IconButton(
                    onPressed: () {
                      setState(() {
                        obscurePassword = !obscurePassword;
                      });
                    },
                    icon: Icon(
                      obscurePassword
                          ? Icons.visibility_off_outlined
                          : Icons.visibility_outlined,
                      color: Colors.blueGrey,
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 30),

              SizedBox(
                height: 55,
                child: ElevatedButton(
                  onPressed: login,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: teal,
                    foregroundColor: Colors.white,
                    elevation: 0,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(13),
                    ),
                  ),
                  child: const Text(
                    'Masuk',
                    style: TextStyle(
                      fontSize: 17,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 35),

              const Text(
                'Bless POS Â© 2026',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: Color(0xFF94A3B8),
                  fontSize: 12,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _fieldLabel(String label) {
    return Text(
      label,
      style: const TextStyle(
        color: Color(0xFF243B53),
        fontWeight: FontWeight.w600,
        fontSize: 14,
      ),
    );
  }

  InputDecoration _inputDecoration(
      String hint,
      IconData icon,
      ) {
    return InputDecoration(
      hintText: hint,
      prefixIcon: Icon(
        icon,
        color: navy,
      ),
      filled: true,
      fillColor: const Color(0xFFF8FAFC),
      contentPadding: const EdgeInsets.symmetric(
        horizontal: 16,
        vertical: 17,
      ),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(
          color: Color(0xFFD8E0EA),
        ),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(
          color: Color(0xFFD8E0EA),
        ),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(
          color: teal,
          width: 1.5,
        ),
      ),
    );
  }
}

