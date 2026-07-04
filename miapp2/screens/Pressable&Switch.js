import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View, Pressable, Switch } from 'react-native';
import React, { useState } from 'react';

export default function PressableScreen() {
  const [ButtonText, setButtonText] = useState('pushame');
  const [isDarkMode, setIsDarkMode] = useState(false);

  return (
    <View style={[styles.container, {backgroundColor: isDarkMode ? "#000000":"#ffffff"}]}>
      <StatusBar style={isDarkMode ? "light" : "dark"}  />
      <Text style={{color: isDarkMode ? "#ffffff" : "#000000"}}>Pantalla: Pressable & Switch</Text>

      <Pressable
        style={styles.button}
        onPress={() => {
          console.log("presiono el boton");
          setButtonText("boton presionado ");
        }}

        onPressIn={() => {
          console.log("se acaba de presionar el boton");
        }}

        onPressOut={() => {
          console.log("se solto el boton");
        }}

        onLongPress={() => {
          console.log("presion prolongada");
          setButtonText("presion prolongada ");
        }}
      >
        <Text style={styles.buttonText} > {ButtonText}</Text>
      </Pressable>

      <Text style={[styles.text, {color: isDarkMode ? "#ffffff":"#000000"}]}>{isDarkMode ? "Modo Obscuro" : "Modo Claro"}</Text>
      <Switch
        value={isDarkMode}
        onValueChange={() => setIsDarkMode(previousState => !previousState)}
        trackColor={{ false: "#efe700", true: "#8c00ff" }}
        thumbColor={"#ef0000"}
      />
    </View>
  );
}
const styles = StyleSheet.create({
    container: {
        flex: 1, 
        justifyContent: "center",
        alignItems: "center",
        padding: 20
    },
    button: {
        backgroundColor: "blue",
        padding: 20,
        borderRadius: 10,
        marginBottom: 50 
    },
    buttonText: {
        fontSize: 20,
        color: "white",
        textAlign: "center"
    },
    switchContainer: {
        flexDirection: "row",
        alignItems: "center",
        justifyContent: "space-between",
        width: "80%", 
        paddingHorizontal: 10
    },
    text: {
        fontSize: 18,
        fontWeight: "bold"
    }
});