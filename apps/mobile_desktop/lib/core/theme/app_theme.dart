
import 'package:flutter/material.dart';

class AppTheme {
  static const Color primaryColor = Color(0xFF2563EB);

  static ThemeData get light {
    return ThemeData(
      useMaterial3: true,
      fontFamily: 'Roboto',
      colorScheme: ColorScheme.fromSeed(
        seedColor: primaryColor,
      ),
    );
  }
}
