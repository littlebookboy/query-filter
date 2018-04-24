<?php

namespace Kblais\QueryFilter;

/**
 * 過濾器介面 Interface
 */
interface QueryFilterInterface
{
    /**
     * 建立本地錢包對應遊戲站「帳號」
     *
     * @param string $account 帳號
     * @param array $params 參照表參數
     * @return mixed
     */
    public function build(string $account, array $params = []);

    /**
     * 取得本地錢包對應遊戲站帳號「餘額」
     *
     * @param string $account 帳號
     * @param array $params 參照表參數
     * @return float
     */
    public function balance(string $account, array $params = []);

    /**
     * 「增加」本地錢包對應遊戲站帳號「點數」
     *
     * @param string $account
     * @param float $amount
     * @return float
     */
    public function deposit(string $account, float $amount);

    /**
     * 「回收」本地錢包對應遊戲站帳號「點數」
     *
     * @param string $account
     * @param float $amount
     * @return float
     */
    public function withdraw(string $account, float $amount);

    /**
     * 調整點數
     *
     * @param string $account
     * @param float $finalBalance 經過異動點數後，最後的 balance 餘額應為多少
     * @param array $params
     * @return mixed
     */
    public function adjust(string $account, float $finalBalance, array $params = []);

    /**
     * 透過錢包 ID 取得夾心連結
     *
     * @param string $walletId
     * @return \SuperPlatform\StationWallet\Models\StationLoginRecord
     */
    public function play(string $walletId);

    /**
     * 向遊戲站端請求遊玩連結
     *
     * @param string $account
     * @param array $options
     * @return array
     */
    public function passport(string $account, array $options = []);
}