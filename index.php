<?php
if (isset($_COOKIE['foodData'])) {
  $food_data = $_COOKIE['foodData'];
  $food_data = json_decode($food_data);

  function get_value_from_price($price)
  {
    $value = str_replace('R$', '', $price);
    $value = str_replace(',', '.', $value);
    return floatval($value);
  }

  function format_price($sub_total)
  {
    return number_format($sub_total, 2, ',', '.');
  }

  function sum_values($value1, $value2)
  {
    return $value1 + $value2;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css?v=<?php echo time() ?>" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <title>Jonh's Kithcen</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      outline: none;
      border: none;
      font-family: "Roboto", sans-serif;
    }

    body {
      height: 100vh;
      width: 100%;
      background: url("img/bg.png");
    }

    header {
      display: flex;
      align-items: center;
      flex-direction: column;
      padding: 1.5rem;
      color: #00503d;
    }

    header h1 {
      align-self: flex-start;
    }

    header h2 {
      color: #535353;
      text-transform: uppercase;
      margin-left: 1rem;
    }

    #order-button {
      position: absolute;
      left: 5%;
      padding: 1.5rem;
      color: #fff;
      background-color: #00503d;
      border-radius: 25px;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.367);
      transition: all 0.2s ease;
    }

    #order-button:hover {
      border: solid 2px #00503d;
      color: #00503d;
      background: transparent;
    }

    #bg-food {
      width: 25rem;
      height: 25rem;
      margin: auto;
      background-image: url("img/salmon-salad.svg");
      background-repeat: no-repeat;
      background-size: cover;
      transition: all 0.2s ease-in;
    }

    .fade {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 10;
    }

    #food-cart {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 2rem 1rem;
      width: 20rem;
      border-radius: 25px;
      font-size: 1.2rem;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.367);
      cursor: pointer;
      z-index: 15;
    }

    #food-cart h3 {
      text-align: center;
    }

    #food-cart img {
      width: 5rem;
    }

    #food-cart .food-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      margin: 1rem 0;
    }

    #food-cart .food-item .food-description {
      width: 10rem;
    }

    #food-cart .food-item .food-quantinty {
      background-color: black;
      color: white;
      border-radius: 25px;
      padding: 0 0.5rem;
      font-size: 0.8rem;
    }

    #food-cart .totals {
      padding: 1rem;
      background-color: rgba(0, 255, 255, 0.193);
      border-radius: 25px;
      font-weight: 700;
      font-size: 1rem;
    }

    #food-cart .totals .totals-item {
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
    }

    #food-cart .totals .totals-item.time {
      border: 2px dotted;
      border-radius: 25px;
      padding: 0.5rem;
    }

    #food-cart .total-to-pay {
      display: flex;
      justify-content: space-between;
      margin-top: 1rem;
      padding: 1rem;
      font-size: 1rem;
      background-color: black;
      color: white;
      border-radius: 0 25px 25px 0;
    }

    #food-cart .total-to-pay button {
      padding: 0.5rem 2rem;
      border-radius: 25px;
      font-size: 1.2rem;
      cursor: pointer;
    }

    #food-cart .total-to-pay strong {
      font-size: 1.2rem;
    }

    .menu {
      margin-top: -1rem;
      display: flex;
      justify-content: space-around;
    }

    .food-card {
      margin-top: -2.5rem;
      background: white;
      width: 15rem;
      text-align: center;
      border-radius: 25px;
      box-shadow: 15px 15px 15px rgba(0, 0, 0, 0.158);
      transition: all 0.2s ease-in-out;
    }

    .cover {
      width: 15rem;
      height: 13.5rem;
      position: absolute;
      cursor: pointer;
      z-index: 5;
    }

    .food-card.selected-food {
      transform: scale(1.1);
      box-shadow: 0 0 15px rgba(0, 255, 255, 0.396);
    }

    .food-card .food-image {
      margin-top: -3rem;
      width: 10rem;
    }

    .food-card .food-name {
      font-weight: 700;
    }

    .food-card .food-price {
      font-weight: 700;
      color: #acacac;
    }

    .food-card .food-price::after {
      content: "";
      display: block;
      width: 90%;
      height: 1px;
      background-color: #acacac;
      margin: 1rem auto 0;
    }

    .food-card .food-info {
      padding: 1.5rem;
      display: flex;
      justify-content: space-around;
    }

    .food-card .food-info .food-quantinty {
      width: 2rem;
      z-index: 10;
    }
  </style>
</head>

<body>
  <header>
    <h1>John's Kitchen</h1>
    <h2>Menu</h2>
  </header>

  <button id="order-button">Fazer Pedido</button>

  <div id="bg-food"></div>

  <?php
  if (isset($food_data) && !empty($food_data)) {

    $values = array();
    $minutes = array();
    $delivery_charge = 2.50;

    echo '
      <div class="fade"></div>
      <div id="food-cart">
      <h3>Carrinho <i class="fa-solid fa-cart-shopping"></i></h3>
      ';
    foreach ($food_data as $food) {
      echo '
        <div class="food-item">
          <img src=' . $food->image . ' alt="comida">
          <div class="food-description">
            <h5 class="food-name">' . $food->name . '</h5>
            <h6 class="food-time">' . $food->time . '</h6>
            <strong class="food-quantinty">' . $food->quantinty . '</strong>
            <small class="food-price">' . $food->price . '</small>
          </div>
        </div>
        ';

      $value = get_value_from_price($food->price);
      $multiplied_value = $value * $food->quantinty;
      array_push($values, $multiplied_value);
      array_push($minutes, intval($food->time));
    }

    $sub_total = (array_reduce($values, "sum_values"));
    $total_minutes = (array_reduce($minutes, "sum_values"));

    $total_to_pay = $sub_total + $delivery_charge;

    echo '
        <div class="totals">
        <div class="totals-item">
          <span>Sub total</span>
          <span>R$' . format_price($sub_total) . '</span>
        </div>
        <div class="totals-item">
          <span>Frete</span>
          <span>R$' . format_price($delivery_charge) . '</span>
        </div>
        <div class="totals-item time">
          <span>Tempo</span>
          <span>' . $total_minutes . ' minutos</span>
        </div>
      </div>

      <div class="total-to-pay">
      <div>
        Total a Pagar <br>
        <strong>R$ ' . format_price($total_to_pay) . '</strong>
      </div>
      <button> >>> </button>
    </div>
  </div>
        ';
  }
  ?>

  <main class="menu">
    <div class="food-card">
      <div id="salmon-salad" class="cover"></div>
      <img class="food-image" src="img/salmon-salad.svg" alt="salada de salmão" />
      <h3 class="food-name">Salada de Salmão</h3>
      <small class="food-price">R$15,00</small>
      <div class="food-info">
        <div>
          <h5>Tempo</h5>
          <span class="food-time">15 minutos</span>
        </div>

        <div>
          <h5>Quantidade</h5>
          <input class="food-quantinty" type="number" value="2" min="1" />
        </div>
      </div>
    </div>
    <div class="food-card">
      <div id="raw-salmon-salad" class="cover"></div>
      <img class="food-image" src="img/raw-salmon-salad.svg" alt="salada de salmão" />
      <h3 class="food-name">Salada de Salmão Cru</h3>
      <small class="food-price">R$17,00</small>
      <div class="food-info">
        <div>
          <h5>Tempo</h5>
          <span class="food-time">10 minutos</span>
        </div>

        <div>
          <h5>Quantidade</h5>
          <input class="food-quantinty" type="number" value="1" min="1" />
        </div>
      </div>
    </div>
    <div class="food-card">
      <div id="salmon-stack" class="cover"></div>
      <img class="food-image" src="img/salmon-stack.svg" alt="salada de salmão" />
      <h3 class="food-name">Filé de Salmão</h3>
      <small class="food-price">R$30,00</small>
      <div class="food-info">
        <div>
          <h5>Tempo</h5>
          <span class="food-time">25 minutos</span>
        </div>

        <div>
          <h5>Quantidade</h5>
          <input class="food-quantinty" type="number" value="2" min="1" />
        </div>
      </div>
    </div>
    <div class="food-card">
      <div id="chicken-with-rice" class="cover"></div>
      <img class="food-image" src="img/chicken-with-rice.svg" alt="salada de salmão" />
      <h3 class="food-name">Galinha com Arroz</h3>
      <small class="food-price">R$75,00</small>
      <div class="food-info">
        <div>
          <h5>Tempo</h5>
          <small class="food-time">35 minutos</small>
        </div>

        <div>
          <h5>Quantidade</h5>
          <input class="food-quantinty" type="number" value="3" min="1" />
        </div>
      </div>
    </div>
  </main>

  <script>
    const foodCards = document.querySelectorAll(".food-card");
    const orderButton = document.getElementById("order-button");
    const finishOrderButton = document.querySelector(
      "#food-cart .total-to-pay button"
    );

    const changeBgFood = (food) => {
      const bgFood = document.getElementById("bg-food");
      bgFood.style.backgroundImage = `url(img/${food}.svg)`;
    };

    const getDataFromSelectedCards = () => {
      const foodData = [];
      const selectedCards = document.querySelectorAll(".selected-food");
      console.log(selectedCards);

      selectedCards.forEach((_, index) => {
        const foodName = document.querySelectorAll(".selected-food .food-name")[
          index
        ].textContent;
        const foodPrice = document.querySelectorAll(".selected-food .food-price")[
          index
        ].textContent;
        const foodTime = document.querySelectorAll(".selected-food .food-time")[
          index
        ].textContent;
        const foodQuantinty = document.querySelectorAll(
          ".selected-food .food-quantinty"
        )[index].value;
        const foodImage = document.querySelectorAll(".selected-food .food-image")[
          index
        ].src;

        foodData.push({
          name: foodName,
          price: foodPrice,
          time: foodTime,
          quantinty: foodQuantinty,
          image: foodImage,
        });
      });
      console.log(JSON.stringify(foodData));
      document.cookie = `foodData=${JSON.stringify(
    foodData
  )}; expires=${new Date().toDateString()}`;
      const data = document.cookie;
      console.log(data);
    };

    const selectCard = (event) => {
      const clickedElement = event.target;
      clickedElement.parentNode.classList.toggle("selected-food");
      if (clickedElement.hasAttribute("id")) {
        changeBgFood(clickedElement.id);
      }
    };

    const order = () => {
      getDataFromSelectedCards();
      location.reload();
    };

    foodCards.forEach((card) => card.addEventListener("click", selectCard));
    const finishOrder = () => {
      location.reload();
      document.cookie = `foodData=""; expires=Thu, 01 Jan 1970 00:00:01 GMT;`;
    };

    orderButton.addEventListener("click", order);
    finishOrderButton && finishOrderButton.addEventListener("click", finishOrder);
  </script>
</body>

</html>