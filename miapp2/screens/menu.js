/*zona1: importaciones de componentes y archivos */
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View, Button} from 'react-native';
import React,{useState} from 'react';
import TarjetasScreen from './tarjetas';
import SafeAreaScreen from './SafeAreaView';
import PressableScreen from './Pressable&Switch';
import ActivityIndicatorScreen from './ActivityIndicator_KeyboardAvoidingView';
import FlatListScreen from './FlatList&SectionList';
import ModalScreen from './Modal&BottomSheet';
import TextInputScreen from './TextInput&Alert';
import ImageBackgroundScreen from './mageBackgroung&SlapshScreen';

/*zona2: main - hogar de los componetes */
export default function MenuScreen() {

    const [screen, setScreen]=useState('menu');

    switch(screen){
        case 'tarjetas':
            return <TarjetasScreen/>
        case 'safeArea':
            return <SafeAreaScreen/>
        case 'pressable':
            return <PressableScreen/>
        case 'activityIndicator':
            return <ActivityIndicatorScreen/>
        case 'flatList':
            return <FlatListScreen/>
        case 'modal':
            return <ModalScreen/>
        case 'textInput':
            return <TextInputScreen/>
        case 'imageBackground':
            return <ImageBackgroundScreen/>
        
        case 'menu':
            default:
            return (
                <View style={styles.container}>

                    <Text>Menu de Practicas</Text>
                                        
                    <Button onPress={()=> setScreen('tarjetas')} title='Tarjetas'/>
                    <Button onPress={()=> setScreen('safeArea')} title='SafeAreaView'/>
                    <Button onPress={()=> setScreen('pressable')} title='Pressable & Switch'/>
                    <Button onPress={()=> setScreen('activityIndicator')} title='ActivityIndicator'/>
                    <Button onPress={()=> setScreen('flatList')} title='FlatList & SectionList'/>
                    <Button onPress={()=> setScreen('modal')} title='Modal & BottomSheet'/>
                    <Button onPress={()=> setScreen('textInput')} title='TextInput&Alert'/>
                    <Button onPress={()=> setScreen('imageBackground')} title='ImageBackground&Splash'/>

                    <StatusBar style='auto'/>

                </View>
            );
        }

}

/*zona3: estilos y posicionamiento */
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#b0b0b0',
    alignItems: 'center',
    justifyContent: 'center',
    flexDirection:'column',
    marginTop: 50
  },

});