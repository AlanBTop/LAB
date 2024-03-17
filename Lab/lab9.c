#include <stdio.h>
#include <stdlib.h>
int main ()
{
    char letra = 'c';
    int num = 5;
    float num_real = 5.25;
    
    printf("Letra %c \n", letra);
    printf("Codigo ASCII da Letra %d \n", letra);
    printf("inteiro %d \n", num);
    printf("Real %f \n", num_real); 
    
    //Imprimindo tudo em uma única linha
    printf("\n\nLetra: %c\nASCII: %d\nInteiro: %d\nReal: %.2f", letra, letra, num, num_real);
    // imprimindo com como tabulação em uma única linha
    printf("\n\nLetra: %c\tASCII: %d\tInteiro: %d\tReal: %.2f", letra, letra, num, num_real);
    
    system("pause");
    return 0;
}
