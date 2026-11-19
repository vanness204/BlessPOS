
import 'package:flutter/material.dart';

import 'core/theme/app_theme.dart';
import 'features/auth/presentation/pages/login_page.dart';

void main() {
  runApp(const BlessPosApp());
}

class BlessPosApp extends StatelessWidget {
  const BlessPosApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Bless POS',
      theme: AppTheme.light,
      home: const LoginPage(),
    );
  }
}
