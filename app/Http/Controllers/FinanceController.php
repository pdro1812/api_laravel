<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class FinanceController extends Controller
{
    // Adicionar saldo (criar transação positiva)
    public function addBalance($id, $valor)
    {
        $user = User::find($id); // Corrigido de Users para User

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $valor, // valor positivo
            'type' => 'deposit',
            'description' => 'Crédito manual de saldo',
        ]);

        return response()->json(['message' => 'Saldo adicionado com sucesso']);
    }

    // Remover saldo (criar transação negativa)
    public function removeBalance($id, $valor)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $saldoAtual = $user->transactions()->sum('amount');
        if ($valor > $saldoAtual) {
            return response()->json(['message' => 'Saldo insuficiente'], 400);
        }

        Transaction::create([
            'user_id' => $user->id,
            'amount' => -$valor, // valor negativo
            'type' => 'withdrawal',
            'description' => 'Débito manual de saldo',
        ]);

        return response()->json(['message' => 'Saldo removido com sucesso']);
    }

    // Ver saldo atual
    public function balance($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $saldo = $user->transactions()->sum('amount');
        return response()->json(['saldo' => $saldo]);
    }

    //listar todas as transações de todos os usuários
    public function listTransactions()
    {
        $transactions = Transaction::with('user')->get();
        return response()->json($transactions);
    }

    //listar todas as transações de um usuário específico com as informações do usuário
    public function listUserTransactions($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $transactions = $user->transactions()->with('user')->get();
        return response()->json($transactions);
    }

    //remover uma transação específica
    public function removeTransaction($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    //gerar um csv com as movimentacoes dos ultimos 30 dias
    public function generateCSVumMes()
    {
        $transactions = Transaction::where('created_at', '>=', now()->subDays(30))->get();

        $csvFileName = 'movimentacoes_ultimos_30_dias.csv';
        $handle = fopen($csvFileName, 'w');

        // Adiciona o cabeçalho do CSV
        fputcsv($handle, ['ID', 'User ID', 'Amount', 'Type', 'Description', 'Created At']);

        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->id,
                $transaction->user_id,
                $transaction->amount,
                $transaction->type,
                $transaction->description,
                $transaction->created_at,
            ]);
        }

        fclose($handle);

        return response()->download($csvFileName)->deleteFileAfterSend(true);
    }

    //gerar um csv com as movimentacoes de um mes especifico
    public function generateCSVmes($mes, $ano)
    {
        $transactions = Transaction::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->get();

        $csvFileName = 'movimentacoes_' . $mes . '_' . $ano . '.csv';
        $handle = fopen($csvFileName, 'w');

        // Adiciona o cabeçalho do CSV
        fputcsv($handle, ['ID', 'User ID', 'Amount', 'Type', 'Description', 'Created At']);

        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->id,
                $transaction->user_id,
                $transaction->amount,
                $transaction->type,
                $transaction->description,
                $transaction->created_at,
            ]);
        }

        fclose($handle);

        return response()->download($csvFileName)->deleteFileAfterSend(true);
    }

    //gerar um csv com todas as movimentacoes
    public function generateCSVall()
    {
        $transactions = Transaction::all();

        $csvFileName = 'movimentacoes.csv';
        $handle = fopen($csvFileName, 'w');

        // Adiciona o cabeçalho do CSV
        fputcsv($handle, ['ID', 'User ID', 'Amount', 'Type', 'Description', 'Created At']);

        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->id,
                $transaction->user_id,
                $transaction->amount,
                $transaction->type,
                $transaction->description,
                $transaction->created_at,
            ]);
        }

        fclose($handle);

        return response()->download($csvFileName)->deleteFileAfterSend(true);
    }
}
