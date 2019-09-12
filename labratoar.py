# URL = 'https://api.telegram.org/bot{}/'.format(TOKEN)
import telebot

API_TOKEN = '947961339:AAGgvcMJffgrUoiscWWC_6QI6TuYaTQpGmI'
bot = telebot.TeleBot(API_TOKEN)
# 95746722

@bot.message_handler(commands=['start', 'help'])
def send_welcome(message):
    bot.reply_to(message, "CRYPTO TRIGGER V 0.7 started")

@bot.message_handler(commands=['notifyme'])
def send_echo(message):
    bot.reply_to(message, "OK. you added to our list..., your ID is {}")

bot.send_message('95746722', 'Bingooooo')

bot.polling()

# user = bot.get_me()
