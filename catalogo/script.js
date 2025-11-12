// Pega os elementos do menu mobile
const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

// Adiciona o evento de clique ao ícone do hambúrguer
hamburger.addEventListener("click", () => {
    // Alterna a classe 'active' no ícone (para o 'X')
    hamburger.classList.toggle("active");
    // Alterna a classe 'active' no menu (para exibir/esconder)
    navMenu.classList.toggle("active");
});

// Opcional: Fechar o menu ao clicar em um link (bom para mobile)
document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
    hamburger.classList.remove("active");
    navMenu.classList.remove("active");
}));