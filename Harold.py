import telebot
from prettytable import PrettyTable

API_TOKEN = '947961339:AAGgvcMJffgrUoiscWWC_6QI6TuYaTQpGmI'
error_report_id = '95746722'
good_margin_report_id = '108521416'


class LocalDB:
    host = 'localhost'
    database = 'altcoin-trader'
    user = 'root'
    password = ''
    session_life = 180


class Harold:
    def dbcreator(date):
        sql_command = "CREATE TABLE IF NOT EXISTS `" + date + "` """ \
                       "(id int(11) NOT NULL AUTO_INCREMENT, " \
                       "markets text DEFAULT NULL COMMENT 'json'," \
                       "averages text DEFAULT NULL COMMENT 'asks and bids average'," \
                       "exchange_rate float DEFAULT 1 COMMENT 'USD to CAD exchange rate'," \
                       "submitted_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP," \
                       "PRIMARY KEY (id))"""
        return sql_command

    def averager(market):
        output = {}
        average = 0
        summeryTable = PrettyTable(['Source', 'Currency', 'Type', 'Total', 'Total Volume', 'Harold Average'])
        for source, string in market.items():
            # Source: Kraken, Catalx, ...
            output.update({source: {}})
            for currency, values in string.items():
                # Currency: BTC, ETH, ...
                # print("-----\n", source, currency)
                output[source].update({currency: {}})
                # Order : Asks , Bids
                for order, value in values.items():
                    output[source][currency].update({order: {}})
                    total_coin = 0
                    total_volume = 0
                    # print(order)
                    # Key: Asks/bids value , Value: Asks/Bids volume
                    for k, v in value.items():
                        total_coin = round((k * v) + total_coin, 3)
                        total_volume = round(v + total_volume, 3)
                        if total_volume > 0:
                            average = round(total_coin / total_volume, 3)
                        else:
                            average = 0
                        # print("\t", k, "*", v, '=', round(k * v, 3))
                    summeryTable.add_row([source, currency, order, total_coin, total_volume, average])
                    # print("Total %s = %s | Total %s Volume = %s | Harold %s Average = %s" % (order, total_coin, order, total_volume, order, average))
                    # print("Total", order,  "Volume =", total_volume)
                    # print("Harold", order, "Average=", average, '\n')
                    output[source][currency][order] = average
        print(summeryTable)
        return output

    def spaner(from_currency, to_currency):
        span = round(((to_currency - from_currency)/from_currency) * 100, 4)
        return span

    def alerter(refrence_margin, span, market):
        if span > refrence_margin:
            bot = telebot.TeleBot(API_TOKEN)
            message = '💥 %s = %s ' % (market, span)
            bot.send_message(error_report_id, message)
            bot.send_message(good_margin_report_id, message)

    def crash_reporter(reciver_id, message):
        bot = telebot.TeleBot(API_TOKEN)
        bot.send_message(reciver_id, message)