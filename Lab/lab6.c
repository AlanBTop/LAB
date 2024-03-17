#include <stdio.h>

int main() {
    float floatValue = 3.14;
    double doubleValue = 3.14159; 

    // Imprime valores em decimal
    printf("Valor float: %f\n", floatValue) ;
    printf("Valor Double: %lf\n", doubleValue) ;

    // Atribui valores usando notação científico
    floatValue = 0.314e1;      // 0.314 * 10^1 = 3.14
    doubleValue = 3.14159e0;  // 3.14159 * 10^0

    // Print values in scientific notation
    printf("Valor float: %f\n", floatValue); 
    printf("Valor double: %lf\n", doubleValue) ;

    printf ("Valor float em notação científica: %e\n", floatValue);
    printf ("Valor double em notação científica: %le\n", doubleValue) ;
    
    return 0;
}