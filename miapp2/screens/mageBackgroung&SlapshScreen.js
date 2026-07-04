import { StyleSheet, Text, View } from 'react-native';
import React from 'react';

export default function ImageBackgroundScreen() {
  return (
    <View style={styles.container}>
      <Text>Pantalla: Image Background & Splash Screen</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
