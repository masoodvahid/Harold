class LocalDB:
    host = 'localhost'
    database = 'altcoin-trader'
    user = 'root'
    password = ''
    session_life = 180


class Harold:
    def averager(market):
        output = {}
        for source, string in market.items():
            # Source: Kraken, Catalx, ...
            output.update({source: {}})
            for currency, values in string.items():
                # Currency: BTC, ETH, ...
                print("-----\n", source, currency)
                output[source].update({currency: {}})
                # Order : Asks , Bids
                for order, value in values.items():
                    output[source][currency].update({order: {}})
                    total_coin = 0
                    total_volume = 0
                    print(order)
                    # Key: Asks/bids value , Value: Asks/Bids volume
                    for k, v in value.items():
                        total_coin = round((k * v) + total_coin, 3)
                        total_volume = round(v + total_volume, 3)
                        if total_volume > 0 :
                            average = round(total_coin / total_volume, 3)
                        else:
                            average = 0
                        # print("\t", k, "*", v, '=', round(k * v, 3))
                    print("Total",order,"=", total_coin)
                    print("Total",order,"Volume =", total_volume)
                    print("Harold",order,"AVG =", average, '\n')
                    output[source][currency][order] = average
        return output