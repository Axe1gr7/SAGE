import React, { useState } from 'react';
import { StyleSheet, Text, View, Button, TextInput, Platform, Alert, Keyboard } from 'react-native';

export default function TextInputScreen() {

  const[nombre, setNombre] = useState('');
  const[password, setPassword] = useState('');
  const[edad, setEdad] = useState('');
  const[correo, setCorreo] = useState('');

  const procesarRegistro = () => {
    if (Platform.OS !== 'web') Keyboard.dismiss();
    if (!nombre || !password || !edad || !correo) {
      alertasManager("validacion", "todos los campos son obligatorios ");
      return;
    }

    alertasManager("exito", `registro procesado para: ${nombre}`);
  };

  const alertasManager = (titulo, mensaje) => {
    if (Platform.OS === 'web') {
      alert(`${titulo}: ${mensaje}`);
    } else {
      Alert.alert(titulo, mensaje);
    }
  }
  return (
    <View style={styles.container}>

      {}
      <TextInput 
      style={styles.input}
      placeholder="nombre completo"
      value={nombre}
      onChangeText={setNombre}
      />


      {}
      <TextInput 
      style={styles.input}
      placeholder="contraseña"
      value={password}
      onChangeText={setPassword}
      secureTextEntry ={true}
      />

      {}
      <TextInput 
      style={styles.input}
      placeholder="ingresa tu edad"
      value={edad}
      onChangeText={setEdad}
      keyboardType='numeric'
      maxLength={5}
      />

      {}
      <TextInput 
      style={styles.input}
      placeholder="correo"
      value={correo}
      onChangeText={setCorreo}
      keyboardType="email-address"
      autoCapitalize="none"
      autoCorrect={false}
      />

      {}
      <Button
      title="registrar"
      onPress={procesarRegistro}
      />

    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, justifyContent:
    'center', padding: 20, 
    backgroundColor: '#f5f6fa' },
  input: { 
    borderWidth: 1, 
    borderColor: '#dcdde1',
    padding: 12, 
    borderRadius: 8, 
    marginBottom: 12, 
    backgroundColor: '#fff' }
});