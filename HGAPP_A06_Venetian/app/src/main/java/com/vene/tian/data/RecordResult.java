package com.vene.tian.data;

import java.util.List;

public class RecordResult {


    /**
     * total : 30
     * num_per_page : 10
     * currentpage : 0
     * page_count : 3
     * perpage : 10
     * rows : [{"Checked":"0","Gold":"132","Type":"S","AddDate":"2018-06-30","notes":"支付宝扫码-sssss11111","Order_code":"20180630044532118648","Date":"2018-06-30 16:45:00","Name":"张三","Phone":"","Contact":"","Bank":"支付宝扫码","Bank_Account":"支付宝","Bank_Address":"支付宝"},{"Checked":"0","Gold":"100","Type":"S","AddDate":"2018-06-30","notes":"银行柜台-111111","Order_code":"20180630042018732456","Date":"2018-06-30 16:02:00","Name":"张三","Phone":"","Contact":"","Bank":"农业银行","Bank_Account":"6228480789741******","Bank_Address":"联系客服索取"}]
     */

    private int total;
    private int num_per_page;
    private int currentpage;
    private int page_count;
    private int perpage;
    private List<RowsBean> rows;

    public int getTotal() {
        return total;
    }

    public void setTotal(int total) {
        this.total = total;
    }

    public int getNum_per_page() {
        return num_per_page;
    }

    public void setNum_per_page(int num_per_page) {
        this.num_per_page = num_per_page;
    }

    public int getCurrentpage() {
        return currentpage;
    }

    public void setCurrentpage(int currentpage) {
        this.currentpage = currentpage;
    }

    public int getPage_count() {
        return page_count;
    }

    public void setPage_count(int page_count) {
        this.page_count = page_count;
    }

    public int getPerpage() {
        return perpage;
    }

    public void setPerpage(int perpage) {
        this.perpage = perpage;
    }

    public List<RowsBean> getRows() {
        return rows;
    }

    public void setRows(List<RowsBean> rows) {
        this.rows = rows;
    }

    public static class RowsBean {
        /**
         * Checked : 0
         * Gold : 132
         * Type : S
         * AddDate : 2018-06-30
         * notes : 支付宝扫码-sssss11111
         * Order_code : 20180630044532118648
         * Date : 2018-06-30 16:45:00
         * Name : 张三
         * Phone :
         * Contact :
         * Bank : 支付宝扫码
         * Bank_Account : 支付宝
         * Bank_Address : 支付宝
         */

        private String Checked;
        private String Gold;
        private String Type;
        private String AddDate;
        private String notes;
        private String Order_code;
        private String Date;
        private String Name;
        private String Phone;
        private String Contact;
        private String Bank;
        private String Bank_Account;
        private String Bank_Address;
        private String From;
        private String To;

        public String getChecked() {
            return Checked;
        }

        public void setChecked(String Checked) {
            this.Checked = Checked;
        }

        public String getGold() {
            return Gold;
        }

        public void setGold(String Gold) {
            this.Gold = Gold;
        }

        public String getType() {
            return Type;
        }

        public void setType(String Type) {
            this.Type = Type;
        }

        public String getAddDate() {
            return AddDate;
        }

        public void setAddDate(String AddDate) {
            this.AddDate = AddDate;
        }

        public String getNotes() {
            return notes;
        }

        public void setNotes(String notes) {
            this.notes = notes;
        }

        public String getOrder_code() {
            return Order_code;
        }

        public void setOrder_code(String Order_code) {
            this.Order_code = Order_code;
        }

        public String getDate() {
            return Date;
        }

        public void setDate(String Date) {
            this.Date = Date;
        }

        public String getName() {
            return Name;
        }

        public void setName(String Name) {
            this.Name = Name;
        }

        public String getPhone() {
            return Phone;
        }

        public void setPhone(String Phone) {
            this.Phone = Phone;
        }

        public String getContact() {
            return Contact;
        }

        public void setContact(String Contact) {
            this.Contact = Contact;
        }

        public String getBank() {
            return Bank;
        }

        public void setBank(String Bank) {
            this.Bank = Bank;
        }

        public String getBank_Account() {
            return Bank_Account;
        }

        public void setBank_Account(String Bank_Account) {
            this.Bank_Account = Bank_Account;
        }

        public String getBank_Address() {
            return Bank_Address;
        }

        public void setBank_Address(String Bank_Address) {
            this.Bank_Address = Bank_Address;
        }

        public String getFrom() {
            return From;
        }

        public void setFrom(String from) {
            From = from;
        }

        public String getTo() {
            return To;
        }

        public void setTo(String To) {
            this.To = To;
        }
    }
}
