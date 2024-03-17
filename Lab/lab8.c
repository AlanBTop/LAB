#include <stdio.h>
#include <stdlib.h>           //usado para system("")

int main () {
    int x = 10;
    printf("%d\n", x);       // escrita de um inteiro
    
    float y = 5.1452;        
    //escrita de um valor inteiro e outro real
    printf("%d%f\n", x, y);  
    //adicionando espaços entre os valores
    printf("%d %f\n", x, y);

    return 0;
}