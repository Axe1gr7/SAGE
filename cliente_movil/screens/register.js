import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, KeyboardAvoidingView, Platform, Alert, ActivityIndicator } from 'react-native';
import { Feather } from '@expo/vector-icons';
import BrandBackground from '../components/BrandBackground';
import LiquidButton from '../components/LiquidButton';
import { API_URL } from '../env';

// evita quedarse “cargando” por init; muestra loader 2s mínimo
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

/*zona2: main - hogar de los componentes */
export default function RegisterScreen({ navigate, theme }) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [matricula, setMatricula] = useState('');
  const [carrera, setCarrera] = useState('sistemas');
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleRegister = async () => {
    if (!name || !email || !password || !matricula) {
      Alert.alert("Campos requeridos", "Por favor completa todos los campos.");
      return;
    }

    setLoading(true);
    try {
      const payload = {
        nombre_completo: name,
        matricula: matricula,
        correo: email,
        contrasena: password,
        carrera: carrera
      };

      const response = await fetch(`${API_URL}/auth/registro`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.detail || "Error al registrarse");
      }

      Alert.alert("Registro Exitoso", "Tu cuenta ha sido creada. Inicia sesión.", [
        { text: "Aceptar", onPress: () => navigate('Login') }
      ]);

    } catch (error) {
      Alert.alert("Error", error.message);
    } finally {
      await sleep(2000);
      setLoading(false);
    }
  };

  return (
    <BrandBackground theme={theme}>
      <KeyboardAvoidingView
        style={styles.container}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      >
        <View style={styles.content}>
          <View style={styles.header}>
            <Text style={[styles.title, { color: theme.colors.primary }]}>Crear Cuenta</Text>
            <Text style={[styles.subtitle, { color: theme.colors.textSecondary }]}>Únete a SAGE 2.0</Text>
          </View>

          <View style={[styles.glassCard, { backgroundColor: theme.colors.cardBg, borderColor: theme.colors.glassBorder }]}>

            <View style={[styles.inputContainer, { borderColor: theme.colors.glassBorder }]}>
              <Feather name="user" size={18} color={theme.colors.textSecondary} style={styles.icon} />
              <TextInput
                style={[styles.input, { color: theme.colors.textPrimary }]}
                placeholder="Nombre Completo"
                placeholderTextColor={theme.colors.textSecondary}
                value={name}
                onChangeText={setName}
              />
            </View>

            <View style={[styles.inputContainer, { borderColor: theme.colors.glassBorder }]}>
              <Feather name="hash" size={18} color={theme.colors.textSecondary} style={styles.icon} />
              <TextInput
                style={[styles.input, { color: theme.colors.textPrimary }]}
                placeholder="Matrícula"
                placeholderTextColor={theme.colors.textSecondary}
                value={matricula}
                onChangeText={setMatricula}
                keyboardType="number-pad"
              />
            </View>

            <View style={[styles.inputContainer, { borderColor: theme.colors.glassBorder }]}>
              <Feather name="mail" size={18} color={theme.colors.textSecondary} style={styles.icon} />
              <TextInput
                style={[styles.input, { color: theme.colors.textPrimary }]}
                placeholder="Correo Institucional"
                placeholderTextColor={theme.colors.textSecondary}
                value={email}
                onChangeText={setEmail}
                keyboardType="email-address"
                autoCapitalize="none"
              />
            </View>

            <View style={[styles.inputContainer, { borderColor: theme.colors.glassBorder }]}>
              <Feather name="lock" size={18} color={theme.colors.textSecondary} style={styles.icon} />
              <TextInput
                style={[styles.input, { color: theme.colors.textPrimary }]}
                placeholder="Contraseña"
                placeholderTextColor={theme.colors.textSecondary}
                value={password}
                onChangeText={setPassword}
                secureTextEntry={!showPassword}
              />
              <TouchableOpacity onPress={() => setShowPassword(!showPassword)} style={styles.eyeIcon}>
                <Feather name={showPassword ? "eye" : "eye-off"} size={18} color={theme.colors.textSecondary} />
              </TouchableOpacity>
            </View>

            {loading ? (
                <ActivityIndicator size="large" color={theme.colors.primary} style={styles.buttonSpacing} />
            ) : (
                <LiquidButton
                  title="Registrarse"
                  theme={theme}
                  onPress={handleRegister}
                  style={styles.buttonSpacing}
                />
            )}

            <TouchableOpacity onPress={() => navigate('Login')} style={styles.linkContainer}>
              <Text style={[styles.linkText, { color: theme.colors.primary }]}>¿Ya tienes cuenta? Inicia sesión</Text>
            </TouchableOpacity>
          </View>
        </View>
      </KeyboardAvoidingView>
    </BrandBackground>
  );
}

/*zona3: estilos y posicionamiento */
const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  content: {
    flex: 1,
    justifyContent: 'center',
    padding: 24, // Grid 8px
  },
  header: {
    alignItems: 'center',
    marginBottom: 40,
  },
  title: {
    fontSize: 32,
    fontWeight: '800',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    fontWeight: '400',
  },
  glassCard: {
    borderRadius: 24,
    padding: 24,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.05,
    shadowRadius: 10,
    elevation: 2,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    borderRadius: 12,
    borderWidth: 1,
    marginBottom: 16,
    paddingHorizontal: 16,
    height: 52,
    backgroundColor: '#FAFAFA',
  },
  icon: {
    marginRight: 10,
  },
  eyeIcon: {
    padding: 8,
  },
  input: {
    flex: 1,
    height: '100%',
    fontSize: 15,
  },
  buttonSpacing: {
    marginTop: 8,
    marginBottom: 16,
  },
  linkContainer: {
    alignItems: 'center',
    marginTop: 8,
  },
  linkText: {
    fontSize: 14,
    fontWeight: '600',
  },
});
