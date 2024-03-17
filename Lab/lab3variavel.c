#include <stdio.h>
#include <stdlib.h>

int main() {
    int num;
    printf("O valor de num e: %d\n", num);         //imprrimi o valor

    num = 10; 
    printf("O valor de num e: %d\n", num);

    printf("abrir paint \n");
    system("pause");                              //experimente trocar'pause' por 'ls - l' no gedit linux
    
    system("mspaint");                            //abre o paint
    return 0;
}