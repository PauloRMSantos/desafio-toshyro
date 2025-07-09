<?php
class Maquina
{
    public $moedas = [
        '0.01' => 10,
        '0.05' => 10,
        '0.10' => 10,
        '0.25' => 10,
        '0.50' => 10,
        '1.00' => 10
    ];

    public $produtos = [
        'Coca-Cola' => ['preco' => 1.50, 'quantidade' => 10],
        'Agua' => ['preco' => 1.00, 'quantidade' => 10],
        'Pastelina' => ['preco' => 0.30, 'quantidade' => 10],
        //'Pastel' => ['preco' => 2.00, 'quantidade' => 10]
    ];

    public $valorAtual = 0;

    public function inserirMoeda($moedasInput)
    {
        $moedaValores = explode(" ", $moedasInput);
        foreach ($moedaValores as $moeda)
        {
            $moeda = (float)$moeda;
            if (array_key_exists(sprintf("%.2f", $moeda), $this->moedas))
            {
                $this->valorAtual += $moeda;
                $this->moedas[sprintf("%.2f", $moeda)] ++;
            }
        }
    }

    public function selecionarProduto($nomeProduto)
    {
        if(!array_key_exists($nomeProduto, $this->produtos))
        {
            echo "Produto não encontrado\n";
            return;
        }

        $produto = $this->produtos[$nomeProduto];
        $preco = $produto["preco"];
        $quantidade = $produto["quantidade"];

        if ($quantidade <= 0) 
        {
            echo "Produto não encontrado\n";
            return;
        }
        
        if($this->valorAtual >= $preco)
        {
            $this->valorAtual -= $preco;
            $this->produtos[$nomeProduto]["quantidade"] --;
            echo "{$nomeProduto} =" . sprintf("%.2f", $this->valorAtual) . "\n";
        } else
        {
            echo "Dinheiro não é suficiente\n";
        }
    }

    public function troco()
    {
        // Dispensando Troco
        if($this->valorAtual == 0)
        {
            echo "NO_CHANGE\n";
            return; 
        }

        $alterarTroco = $this->valorAtual;
        $moedasTroco = [];
        $moedasDisponiveis = array_keys($this->moedas);
        rsort($moedasDisponiveis);

        foreach ($moedasDisponiveis as $valorMoeda)
        {
            $valorMoeda = (float) $valorMoeda;
            while ($alterarTroco >= $valorMoeda && $this->moedas[sprintf("%.2f", $valorMoeda)] > 0)
            {
                $moedasTroco[] = sprintf("%.2f", $valorMoeda);
                $alterarTroco -= $valorMoeda;
                $this->moedas[sprintf("%.2f", $valorMoeda)] --;
                $alterarTroco = round($alterarTroco, 2);
            }
        }

        if ($alterarTroco > 0)
        {
            echo "NO_COINS";
            foreach ($moedasTroco as $moeda)
            {
                $this->moedas[$moeda]++;
            }
        } else
        {
            echo implode(" ", $moedasTroco) . "\n";
            $this->valorAtual = 0.0;
        }
    }

    public function procesarInputUsuario($input)
    {
        $partes = explode(" ", $input);
        $moedasInput = [];
        $nomeProduto = "";
        $requisicaoTroco = false;
        $produtoRequerido = [];

        foreach ($partes as $parte)
        {
            if(is_numeric($parte))
            {
                $moedasInput[] = $parte;
            } elseif ($parte === "CHANGE")
            {
                $requisicaoTroco = true;
            } else
            {
                $produtoRequerido[] = $parte;
            }
        }

        if (!empty($moedasInput))
        {
            $this->inserirMoeda(implode(" ", $moedasInput));
        } 

        foreach ($produtoRequerido as $produto)
        {
            $this->selecionarProduto($produto);
        }

        if ($requisicaoTroco)
        {
            $this->troco();
        }
    }
    
}