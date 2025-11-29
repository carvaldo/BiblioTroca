# BiblioTroca

# Instalação
- Configure as variáveis de ambiente em .env (se basear em .env.example);
- Prepare o banco de dados
- Instale as dependências: > composer run setup
- Inicialize o projeto:
    > composer run dev

<p>PS: O projeto está pré-preparado para ser executado em um container. Adapte docker-compose.yml de acordo com sua necessidade</p>

# Erros comuns

- "Please provide a valid cache path". Execute: <br>
> mkdir storage/framework/sessions <br>
> mkdir storage/framework/views <br>
> mkdir storage/framework/cache <br>
> chmod -R 755 storage/framework
