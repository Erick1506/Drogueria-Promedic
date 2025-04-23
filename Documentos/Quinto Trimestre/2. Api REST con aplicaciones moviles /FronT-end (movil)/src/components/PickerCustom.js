// src/components/PickerCustom.js
import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Picker } from '@react-native-picker/picker';

const PickerCustom = ({ label, selectedValue, onValueChange, items, style }) => {
  return (
    <View style={styles.container}>
      {label && <Text style={styles.label}>{label}</Text>}
      <Picker
        selectedValue={selectedValue}
        style={[styles.picker, style]}
        onValueChange={onValueChange}
      >
        {items.map((item) => (
          <Picker.Item key={item.id} label={item.name} value={item.id} />
        ))}
      </Picker>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    marginVertical: 8
  },
  label: {
    fontWeight: 'bold',
    marginBottom: 4
  },
  picker: {
    height: 50,
    backgroundColor: '#fff'
  }
});

export default PickerCustom;
