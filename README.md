# Documentação do Projeto Organizze-BK

## Descrição

Este projeto foi criado para fins de estudo de backend, servindo como API para integração com o projeto Organizze no frontend. Ele utiliza PHP puro e segue uma estrutura modularizada para facilitar a manutenção e evolução do código.
Ele está integrado ao repositório [ORGANIZZE](https://github.com/RodriguesEmerson/organizze), que serve como frontend desenvolvido em NextJs. O objetivo principal é estudar e praticar integração entre frontend moderno e backend em PHP.

---

## Estrutura do Projeto

- **publick/**: Contém os endpoints do projeto.
- **src/Auth**: Arquivos para autenticação, validação e criação de usuários.
- **src/Controllers**: Controlles.
- **src/Helpers**: Utils e validadores de dados para o projeto.
- **src/inc**: Pasta com os Headers.
- **src/Models**: Repositories do projeto.

## Requisitos

- PHP 7.4+
- Composer (opcional, caso queira adicionar dependências externas)
- Servidor local (ex: XAMPP)

---

## Tecnologias Utilizadas

- **PHP (backend)**: PHP puro.
- **Next.js (frontend)**: Integração do o repositório ORGANIZZE acima.

## Como Executar

1. Clone o repositório.
   ```sh
   git clone https://github.com/RodriguesEmerson/organizze-bk.git

2. Coloque os arquivos em seu servidor local (ex: `htdocs` do XAMPP).
3. Faça as requisições para os endpoints conforme implementado.

---

## Observações

- O projeto não utiliza frameworks, focando no entendimento dos conceitos básicos de backend em PHP.
- Para integração com o frontend, utilize as rotas e métodos definidos nos arquivos da pasta `src`.

---