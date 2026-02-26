# Gabriel de Oliveira Santana

Decidir fazer um vídeo resumindo os detalhes do projeto em 15 minutos e como reproduzir o teste.

https://drive.google.com/file/d/1FK-xfF9aeppgXdX4Fpsixs6MKSRomY34/view?usp=sharing
## Requisitos

- Docker

## Como instalar

- Baixar o projeto. Link: https://github.com/SantGabriel/test-talent.git
- Rodar o `docker compose up`
- Após iniciar os containers, rodar os comandos do `script.sh` no seu terminal que ele automaticamente vai:
  - Criar os DBs
  - Rodar o composer install
  - Fazer o migrate
  - Rodar o seed de dados

## Detalhamento de rotas

Todas as rotas estão dentro da collection do postman `postman_collection.json`

## Detalhes do projeto

- Todo Cliente é criado a partir do email. Se ele já existe, apenas busco ele do banco
- Ao consultar uma transação. Se ele NÃO estiver em um status final/imutável, eu consulto o gateway para ver se status mudou usando `checkChangeStatus()`
- PaymentGatewayService.php é onde controla todas as possíveis instancias de cada gateway usando Design Pattern
- convertStatus() é um mapa de "tradução" de status de cada gateway para o status do sistema para unificar todos os status. Ex: gt1 -> paid, gt2 -> pago, sistema -> done
- DTO e PaymentData servem pra ajudar a dar auto complete e representam os dados vindos do cliente e do gateway respectivamente
- O Cache do Regis é usado APENAS para guardar o token do gt1. Isso não interfere no requesito de ser RESTful, pois todos os dados necessários continuam vindo do cliente
- Mantive a permissão do usuário de poder usar as rotas de `/transaction` pois não foi citado que podia ou não, apesar de eu acreditar que não deveria