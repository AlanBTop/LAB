#include <stdio.h>

int main() {
    printf("hello world\nisto e uma calculadora basica");
    int num1, num2, resultado;
    
    printf("\nDigite o primeiro numero: ");
    scanf("%d", &num1);

    printf("Digite o segundo numero: ");
    scanf("%d", &num2);

    resultado = num1 + num2;

    printf("O resultado da adiçao e: %d\n", resultado);

    return 0;
}