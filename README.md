# Projeto de Análise de Rede WiFi (PHP MVC)

Este é um guia de implantação para configurar e executar o projeto em um novo computador local (como o XAMPP no Windows).

## 1. Pré-requisitos

O computador de destino deve ter o seguinte software instalado:

1.  **XAMPP**: (Apache + MySQL + PHP). [Baixar aqui](https://www.apachefriends.org/pt_br/index.html)
2.  **Composer**: Gerenciador de dependências do PHP. [Baixar aqui](https://getcomposer.org/Composer-Setup.exe)
3.  **Arquivos do Projeto**: A pasta do seu projeto (ex: `desenvolvimentoprojeto`).
4.  **Arquivo SQL**: O arquivo de exportação do seu banco de dados (ex: `gerenciador_de_sinal.sql`).

---

## 2. Instruções de Instalação

Siga estes passos na ordem correta.

### Fase A: Configurar o Servidor XAMPP

Este é o passo mais crítico e a principal causa de falhas (erro 404).

1.  **Instale o XAMPP** (geralmente em `C:\xampp`).
2.  Abra o **Painel de Controle do XAMPP** e inicie o **Apache** e o **MySQL**.
3.  **Ative o `mod_rewrite`:**
    * No Painel do XAMPP, clique em **Config** (do Apache) > **`httpd.conf`**.
    * Procure (Ctrl+F) pela linha: `#LoadModule rewrite_module modules/mod_rewrite.so`
    * Apague o `#` do início para ativá-la.
4.  **Permita o `.htaccess`:**
    * No mesmo arquivo `httpd.conf`, procure por `<Directory "C:/xampp/htdocs">`.
    * Dentro desse bloco, mude `AllowOverride None` para `AllowOverride All`.
5.  **Salve** o arquivo `httpd.conf`.
6.  **Reinicie o Apache** no painel (Stop > Start).

### Fase B: Configurar o Banco de Dados (MySQL)

1.  No XAMPP, clique em **Admin** (do MySQL) para abrir o **phpMyAdmin**.
2.  Clique em **"Novo"** no menu à esquerda para criar um novo banco de dados.
3.  Digite o nome exato do seu banco: `gerenciador_de_sinal` (ou o nome que você usou).
4.  Clique em **"Criar"**.
5.  Clique no banco que você acabou de criar (na lista à esquerda).
6.  Clique na aba **"Importar"** no menu superior.
7.  Clique em "Escolher arquivo" e selecione o seu arquivo `.sql` (ex: `gerenciador_de_sinal.sql`).
8.  Clique em **"Executar"** no final da página. As tabelas (`residencia`, `comodos`, `medicoes`) devem aparecer.

### Fase C: Instalar os Arquivos do Projeto

1.  **Copie sua pasta** (ex: `desenvolvimentoprojeto`) para dentro da pasta `htdocs` do XAMPP.
    * O caminho final deve ser: `C:\xampp\htdocs\desenvolvimentoprojeto\`
2.  **Instale as dependências (Roteador):**
    * Abra o **Prompt de Comando (CMD)** do Windows.
    * Navegue até a pasta do seu projeto:
        ```bash
        cd C:\xampp\htdocs\desenvolvimentoprojeto
        ```
    * Execute o Composer para baixar as bibliotecas (ele irá criar a pasta `vendor`):
        ```bash
        composer install
        ```

### Fase D: Verificação Final

1.  **Verifique o `.htaccess`:**
    * Abra o arquivo `.htaccess` na raiz do seu projeto.
    * Confirme se a linha `RewriteBase` corresponde ao nome da sua pasta:
        ```apache
        RewriteBase /desenvolvimentoprojeto/
        ```
2.  **Verifique a Conexão com o DB:**
    * Abra o arquivo `models/Database.php`.
    * Confirme se as credenciais do banco estão corretas (o padrão do XAMPP é `root` e senha `''`, que já está no seu arquivo).

---

## 3. Como Testar

1.  Certifique-se de que o Apache e o MySQL estão rodando no XAMPP.
2.  Abra o navegador e acesse a **URL raiz** do seu projeto:

    **`http://localhost/desenvolvimentoprojeto/`**

O site deve carregar a página de gerenciamento de residências.

---

## 4. Solução de Problemas (FAQ)

* **Erro 404 do Apache (Página "Not Found" branca):**
    * **Causa:** O Apache não está configurado.
    * **Solução:** Refaça a **Fase A, Passos 3 e 4** (`httpd.conf`). Verifique se o `mod_rewrite` está ativo e o `AllowOverride` está `All`. **Lembre-se de reiniciar o Apache!**

* **Erro 404 do Roteador (Sua página "Erro 404 Página não encontrada."):**
    * **Causa:** O `.htaccess` está com o `RewriteBase` errado.
    * **Solução:** Verifique se o nome da pasta no `RewriteBase /nomedapasta/` é idêntico ao nome da pasta em `htdocs`.

* **Erro Fatal: `Class "Pecee\SimpleRouter\SimpleRouter" not found...`:**
    * **Causa:** As dependências não foram instaladas.
    * **Solução:** Refaça a **Fase C, Passo 2** (execute `composer install` na pasta do projeto).

* **Erro de SQL: `Access denied for user 'root'@'localhost'`:**
    * **Causa:** A senha do XAMPP do professor é diferente.
    * **Solução:** Edite `models/Database.php` e coloque a senha correta.
