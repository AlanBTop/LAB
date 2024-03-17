#include <stdio.h>
int main() { 
    // Variáveis inteiras
    int inteiro = 10;
    short curto = 20;
    long longo = 30;
    unsigned int inteiroSemSinal = 40;
    unsigned short curtoSemSinal = 50;
    unsigned long longoSemSinal = 60;
    long long longoLongo = 70;

    // Mostrar os valores e tamanhos das variáveis
    printf("Inteiro - Tamanho: %lu bytes - Valor: %d\n", sizeof(inteiro), inteiro);
    printf("Curto   - Tamanho: %lu bytes - Valor: %d\n", sizeof(curto), curto) ;
    printf("Longo   - Tamanho: %lu bytes - Valor: %d\n", sizeof(longo), longo) ;
    printf("Inteiro sem sinal - Tamanho: %lu bytes - Valor: %d\n", sizeof(inteiroSemSinal) , inteiroSemSinal);
    printf("Curto sem sinal   - Tamanho: %lu bytes - Valor: %d\n", sizeof(curtoSemSinal), curtoSemSinal);
    printf("Longo sem sinal   - Tamanho: %lu bytes - Valor: %d\n", sizeof(longoSemSinal), longoSemSinal);
    printf("Longo longo       - Tamanho: %lu bytes - Valor: %d\n", sizeof(longoLongo), longoLongo) ;
    
    return 0;
}